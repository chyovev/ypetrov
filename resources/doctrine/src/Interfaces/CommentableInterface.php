<?php

//    NB!
//
//    Comments across the website share the same structure
//    and therefore can easily be stored in the same table,
//    using a column indicator such as entity_class.
//
//    Unfortunately Doctrine doesn't offer a simple way
//    to implement a one-to-many unidirectional association with
//    such an additional static JOIN column.
//
//    A possible solution is to have separate one-to-many Entity → Comment
//    associations with separate tables which seems kind of clumsy. 
//
//    Therefore, comments will be loaded using a separate query
//    through their own repository for now.
//
//    Classes of Entities which can be commented on
//    should implement this Commentable interface.

namespace Interfaces;

interface Commentable {
    
    ///////////////////////////////////////////////////////////////////////////
    public function getId(): int;
    
}