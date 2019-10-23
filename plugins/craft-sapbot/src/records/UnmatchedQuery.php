<?php

namespace csps\sapbot\records;

use craft\db\ActiveRecord;

class UnmatchedQuery extends ActiveRecord
{
    // Public Methods
    // =========================================================================

    public static function tableName()
    {
        return '{{%sapbot_monitor}}';
    }
}
