<?php

namespace Generics\AdminBundle\Services;

interface DAOServices
{
    public function find($array);
    public function findAll();
    public function findOneById($id);
    public function findOne($array);
    public function save($element);
    public function delete($element);
}