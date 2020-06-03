<?php

namespace /home/infomir/site/tests/out;

use sonrac\SimpleSeed\SimpleSeedWithCheckExists;

/**
 * Class Tests\Seed.
 * Auto generated seed.
 */
class Tests\Seed extends SimpleSeedWithCheckExists
{
    /**
     * @inheritDoc
     */
    public function getTable()
    {
        return "";
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        return [        ];
    }


    /**
     * @inheritDoc
     */
    public function getWhereForRow($data)
    {
        return [
            'index' => $data['`index`'],
        ];
    }

    /**
     * Get delete where data.
     *
     * @param array $data
     *
     * @return array
     */
    public function getDeleteFields($data)
    {
        return [        ];
    }
}
