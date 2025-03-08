<?php
function expose(string $path){
    return new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS);
}
