<?php

namespace EcomDev\MySQL2JSONL\Import;

interface ImportServiceFactory
{
    public function create(): ImportService;
}
