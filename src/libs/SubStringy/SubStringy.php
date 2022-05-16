<?php

namespace TopShelfCraft\Wordsmith\libs\SubStringy;

use Stringy\Stringy;

class SubStringy extends Stringy implements \Countable, \IteratorAggregate, \ArrayAccess
{

    use SubStringyTrait;

}
