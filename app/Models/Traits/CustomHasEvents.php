<?php

namespace App\Models\Traits;

use LogicException;

/**
 * Despite being convenient for data storing, polymorphic
 * tables (such as the comments one) have one significant
 * drawback: no foreign key relationship can be defined in
 * the database, therefore there is no cascade deletion.
 * 
 * To work around this matter, dedicated observers on all
 * the main models should take care of the clean up during
 * deletion, but it is tedious to declare them one by one.
 * 
 * Even though the main models implement the Commentable
 * interface, an observer cannot be registered as a group
 * in the EventServiceProvider as this option works only
 * on fully fledged models.
 * 
 * Since it's impossible to dynamically get a list of all
 * models implementing a certain interface, the alternative
 * is to register the respective polymorphic observer upon
 * model initialization.
 * 
 * @see \App\Models\Traits\HasComments :: initializeHasComments()
 * 
 * NB! Keep in mind that such observers will NOT be listed
 *     via the php artisan event:list command.
 */

trait CustomHasEvents
{
    
    ///////////////////////////////////////////////////////////////////////////
    /**
     * In order for the soon-to-be-registered observer to work,
     * the model should implement the respective interface that
     * the observer relies on.
     * If it doesn't, an exception should be thrown.
     * 
     * @throws LogicException – class not implementing required interface
     * @param  string $interface – class name of interface
     * @return void
     */
    private function validateModelImplementsInterface(string $interface): void {
        if ( ! is_a($this, $interface)) {
            throw new LogicException(sprintf("Class %s does not implement %s interface", class_basename($this), class_basename($interface)));
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Polymorphic observers are registered in the traits in which
     * the respective polymorphic relationship is declared, i.e.
     * in the trait's initializer method.
     * Said method is called on each instance of the object which
     * would cause the observer to be registered and consecutively
     * executed over and over again.
     * To fix this, make sure the observer is not already registered.
     * 
     * @param  string $observerClassName – full class name of the observer
     * @return void
     */
    private function registerObserverToModel(string $observerClassName): void {
        if ( ! $this->isObserverAlreadyRegistered($observerClassName)) {
            $this->registerObserver($observerClassName);
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Since each registered observer has its own listener,
     * in order to find out whether an observer is already
     * registered for a model one should cycle through all
     * already declared listeners.
     * Each model can have multiple observers.
     * Each observer can listen for multiple model events.
     * Each event has therefore its own listener.
     * 
     * NB! Not all listeners are observer listeners, but
     *     the ones that are have the following structure:
     * 
     *     eloquent.<EVENT>: <MODEL> => [
     *         <OBSERVER>@<OBSERVER METHOD>
     *     ]
     * 
     *     e.g.:
     * 
     *     eloquent.created: App\Models\ContactMessage => [
     *         App\Observers\ContactMessageObserver@created
     *     ]
     * 
     * @param  string $observerClassName – full class name of the observer
     * @return bool
     */
    private function isObserverAlreadyRegistered(string $observerClassName): bool {
        // method getEventDispatcher() defined in HasEvents trait
        $rawListeners = $this->getEventDispatcher()->getRawListeners();

        // for an observer to be considered registered for a model,
        // both the model and the observer should be found in the
        // listeners array
        foreach ($rawListeners as $event => $listeners) {
            // if the event is not relavent to the model,
            // move on to the next event
            if (  ! $this->isEventAssociatedWithModel($event)) {
                continue;
            }

            // if even one of the model's listeners relates
            // to the target observer, the observer is
            // considered already registered
            if ($this->isEventListeningForObserver($listeners, $observerClassName)) {
                return true;
            }
        }

        return false;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Check if an event is associated with current model, i.e.
     * if the event name ends with the full name of the model's class.
     * 
     * @param  string $event – name of event, e.g. 'eloquent.created: App\Models\ContactMessage'
     * @return bool
     */
    private function isEventAssociatedWithModel(string $event): bool {
        // the class name contains backslashes – escape them
        $className = get_class($this);
        $regex     = preg_quote($className);
        
        return preg_match("/{$regex}$/", $event);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Cycle through all listeners of an event and check if
     * the target observer is mentioned in one of them.
     * 
     * @param  array<int,mixed> $listeners – all listeners for an event
     * @param  string $observerClassName   – full class name of the observer
     * @return bool
     */
    private function isEventListeningForObserver(array $listeners, string $observerClassName): bool {
        foreach ($listeners as $listener) {
            if ($this->isListenerAssociatedWithObserver($listener, $observerClassName)) {
                return true;
            }
        }

        return false;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Check if a single listener is associated with an observer.
     * 
     * @param  mixed  $listener – an event listener
     * @param  string $observerClassName – full class name of the observer
     * @return bool
     */
    private function isListenerAssociatedWithObserver(mixed $listener, string $observerClassName): bool {
        // listeners can also be callback functions,
        // but observers are always defined as strings
        if ( ! is_string($listener)) {
            return false;
        }

        // the class name contains backslashes – escape them
        $regex = preg_quote($observerClassName);
        
        // e.g. of a listener: 'App\Observers\ContactMessageObserver@created'
        // if the listener starts with the observer's name followed by an '@'
        // character, that's a match
        return preg_match("/^{$regex}@/", $listener);
    }
}