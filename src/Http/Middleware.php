<?php
namespace Young\Framework\Http;

abstract class Middleware{
    public abstract function handle(Request $request);
}