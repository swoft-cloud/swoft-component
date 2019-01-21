<?php

namespace Swoft\Redis\Operator\Hashes;

use Swoft\Redis\Operator\Command;

class HashGetMultiple extends Command
{
    /**
     * [Hash] hMGet
     *
     * @return string
     */
    public function getId()
    {
        return 'hMGet';
    }

    /**
     * {@inheritdoc}
     */
    public function parseResponse($data)
    {
        if ($data === false) {
            return false;
        }

        $result = [];
        $hashKeys = $this->getArgument(1);
        foreach ($data as $key => $value) {
            if (!isset($hashKeys[$key])) {
                continue;
            }

            $value = ($value === null) ? false : $value;
            $result[$hashKeys[$key]] = $value;
        }

        return $result;
    }

}
