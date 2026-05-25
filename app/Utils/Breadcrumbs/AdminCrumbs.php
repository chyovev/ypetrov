<?php

namespace App\Utils\Breadcrumbs;


use App\Models\Book;
use App\Models\ContactMessage;
use App\Models\Essay;
use App\Models\GalleryImage;
use App\Models\Poem;
use App\Models\PressArticle;
use App\Models\StaticPage;
use App\Models\User;
use App\Models\Video;

class AdminCrumbs
{

    ///////////////////////////////////////////////////////////////////////////
    public function get(): Breadcrumb {
        return (new Breadcrumb('home'))
            ->title(__('global.home'))
            ->url(route('admin.home'))
        
            // Home > Dashboard
            ->child('dashboard', function(Breadcrumb $crumb): void {
                $crumb->title(__('global.dashboard'));
            })
        
            // Home > [Static Page]
            ->child('static_pages.edit', function(Breadcrumb $crumb): void {
                $crumb
                    ->title(function(StaticPage $page): string {
                        return $page->title;
                    })
                    ->url(function(StaticPage $page): string {
                        return route('admin.static_pages.edit', $page);
                    });
            })
        
            // Home > Users
            ->child('users.index', function(Breadcrumb $crumb): void {
                $crumb
                    ->title(__('global.users'))
                    ->url(route('admin.users.index'))
        
                    // Home > Users > Create
                    ->child('users.create', function(Breadcrumb $crumb): void {
                        $crumb
                            ->title(__('global.create'))
                            ->url(route('admin.users.create'));
                    })
        
                    // Home > Users > [User]
                    ->child('users.edit', function(Breadcrumb $crumb): void {
                        $crumb
                            ->title(function(User $user): string {
                                return $user->name;
                            })
                            ->url(function(User $user): string {
                                return route('admin.users.edit', $user);
                            });
                    });
            })
        
            // Home > Contact Messages
            ->child('contact_messages.index', function(Breadcrumb $crumb): void {
                $crumb
                    ->title(__('global.contact_messages'))
                    ->url(route('admin.contact_messages.index'))
        
                    // Home > Contact Messages > [Message]
                    ->child('contact_messages.show', function(Breadcrumb $crumb): void {
                        $crumb
                            ->title(function(ContactMessage $message): string {
                                return "{$message->name} (#{$message->id})";
                            })
                            ->url(function(ContactMessage $message): string {
                                return route('admin.contact_messages.show', $message);
                            });
                    });
            })
        
            // Home > Works
            ->child('works', function (Breadcrumb $crumb): void {
                $crumb
                    ->title(__('global.works'))
        
                    // Home > Works > Poems
                    ->child('poems.index', function (Breadcrumb $crumb): void {
                        $crumb
                            ->title(__('global.poems'))
                            ->url(route('admin.poems.index'))
        
                            // Home -> Works > Poems > Create
                            ->child('poems.create', function (Breadcrumb $crumb): void {
                                $crumb
                                    ->title(__('global.create'))
                                    ->url(route('admin.poems.create'));
                            })
        
                            // Home > Works > Poems > [Poem]
                            ->child('poems.edit', function (Breadcrumb $crumb): void {
                                $crumb
                                    ->title(function(Poem $poem): string {
                                        return strip_tags($poem->title);
                                    })
                                    ->url(function(Poem $poem): string {
                                        return route('admin.poems.edit', $poem);
                                    });
                            });
                    })
        
                        // Home > Works > Books
                        ->child('books.index', function(Breadcrumb $crumb): void {
                            $crumb
                                ->title(__('global.books'))
                                ->url(route('admin.books.index'))
    
                                // Home > Works > Books > Create
                                ->child('books.create', function(Breadcrumb $crumb): void {
                                    $crumb
                                        ->title(__('global.create'))
                                        ->url(route('admin.books.create'));
                                })
    
                                // Home > Works > Books > [Book]
                                ->child('books.edit', function(Breadcrumb $crumb): void {
                                    $crumb
                                        ->title(function(Book $book): string {
                                            return $book->title;
                                        })
                                        ->url(function(Book $book): string {
                                            return route('admin.books.edit', $book);
                                });
                        });
    
                    });
            })
        
            // Home > Essays
            ->child('essays.index', function(Breadcrumb $crumb): void {
                $crumb
                    ->title(__('global.essays'))
                    ->url(route('admin.essays.index'))
        
                    // Home > Essays > Create
                    ->child('essays.create', function(Breadcrumb $crumb): void {
                        $crumb
                            ->title(__('global.create'))
                            ->url(route('admin.essays.create'));
                    })
        
                    // Home > Essays > [Essay]
                    ->child('essays.edit', function(Breadcrumb $crumb): void {
                        $crumb
                            ->title(function(Essay $essay): string {
                                return $essay->title;
                            })
                            ->url(function(Essay $essay): string {
                                return route('admin.essays.edit', $essay);
                            });
                    });
            })
        
            // Home > Press Articles
            ->child('press_articles.index', function(Breadcrumb $crumb): void {
                $crumb
                    ->title(__('global.press_articles'))
                    ->url(route('admin.press_articles.index'))
        
                    // Home > Press Articles > Create
                    ->child('press_articles.create', function(Breadcrumb $crumb): void {
                        $crumb
                            ->title(__('global.create'))
                            ->url(route('admin.press_articles.create'));
                    })
        
                    // Home > Press Articles > [Press Article]
                    ->child('press_articles.edit', function(Breadcrumb $crumb): void {
                        $crumb
                            ->title(function(PressArticle $article): string {
                                return $article->title;
                            })
                            ->url(function(PressArticle $article): string {
                                return route('admin.press_articles.edit', $article);
                            });
                    });
            })
        
            // Home > Videos
            ->child('videos.index', function(Breadcrumb $crumb): void {
                $crumb
                    ->title(__('global.videos'))
                    ->url(route('admin.videos.index'))
        
                    // Home > Videos > Create
                    ->child('videos.create', function(Breadcrumb $crumb): void {
                        $crumb
                            ->title(__('global.create'))
                            ->url(route('admin.videos.create'));
                    })
        
                    // Home > Videos > [Video]
                    ->child('videos.edit', function(Breadcrumb $crumb): void {
                        $crumb
                            ->title(function(Video $video): string {
                                return $video->title;
                            })
                            ->url(function(Video $video): string {
                                return route('admin.videos.edit', $video);
                            });
                    });
            })
        
            // Home > Gallery
            ->child('gallery_images.index', function(Breadcrumb $crumb): void {
                $crumb
                    ->title(__('global.gallery'))
                    ->url(route('admin.gallery_images.index'))
        
                    // Home > Gallery > Create
                    ->child('gallery_images.create', function(Breadcrumb $crumb): void {
                        $crumb
                            ->title(__('global.create'))
                            ->url(route('admin.gallery_images.create'));
                    })
        
                    // Home > Gallery > [Gallery]
                    ->child('gallery_images.edit', function(Breadcrumb $crumb): void {
                        $crumb
                            ->title(function(GalleryImage $image): string {
                                return $image->title ?? "#{$image->id}";
                            })
                            ->url(function(GalleryImage $image): string {
                                return route('admin.gallery_images.edit', $image);
                            });
                    });
            });
    }
}