<?php

//TODO: namespacing

class User
{
    private $name;
    private $id;
    private $rank;

    public function __construct(string $name,int $id,iterable $rank) {
        $this->name = $name;
        $this->id = $id;
        $this->rank = $rank;
    }

    public function User() {
        $this->__construct("", 0, null);
    }

    //logic methods
    public function isNew() {
        return $this->id == 0;
    }

   /*
    public function clone() {
        try {
            // TODO: clone profile and clone(id)
        }
    }
   */

   public function getName() : string {
       return $this->name;
   }

   public function setName(string $name) {
       $this->name = $name;
   }

   public function getId() : int {
       return $this->id;
   }

   public function getRank() : iterable {
       return $this->rank;
   }

   public function setRank(iterable $rank) { // TODO theck me
       $this->rank = $rank;
   }


    public function toStrLog() {
       return "User { name: $this->name, id: $this->id, rank: $this->rank }";
    }

}