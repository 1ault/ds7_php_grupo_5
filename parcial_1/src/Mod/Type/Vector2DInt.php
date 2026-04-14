
<?php
declare(strict_types=1);

namespace Root\Program\Mod\Type;

class Vector2DInt
{
    public int x;
    public int y;
    
    public function __construct
    (
        int $x, 
        int $y, 
    ) 
    {
        $this->x = x;
        $this->y = y;
    }
}
