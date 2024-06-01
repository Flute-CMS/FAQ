<?php

namespace Flute\Modules\FAQ\database\Entities;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;

/**
 * @Entity()
 */
class FaqItemBlock
{
    /** @Column(type="primary") */
    public $id;

    /** @BelongsTo(target="FaqItem", nullable=false) */
    public $faqItem;

    /** @Column(type="json") */
    public $json;
}