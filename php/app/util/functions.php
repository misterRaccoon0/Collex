<?php
function expose(string $path){
    return FilesystemIterator($path, FilesystemIterator::SKIP_DOTS);
}
