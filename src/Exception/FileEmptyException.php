<?php

namespace DrupalVm\Exception;

use RuntimeException;
use Symfony\Component\Console\Exception\ExceptionInterface;

class FileEmptyException extends RuntimeException implements ExceptionInterface
{
}
