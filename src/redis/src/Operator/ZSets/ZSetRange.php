<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
namespace Swoft\Redis\Operator\ZSets;

use Swoft\Redis\Operator\Command;

class ZSetRange extends Command
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'zRange';
    }

    /**
     * {@inheritdoc}
     */
    public function parseResponse($data)
    {
        if ($this->withScores()) {
            $result = [];

            for ($i = 0; $i < count($data); ++$i) {
                $result[$data[$i]] = $data[++$i];
            }

            return $result;
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    protected function filterArguments(array $arguments)
    {
        if (count($arguments) === 4) {
            $lastType = gettype($arguments[3]);

            if ($lastType !== 'array' && $arguments[3] != false) {
                // Used for compatibility with older versions
                $arguments[3] = ['WITHSCORES' => true];
                $lastType = 'array';
            }

            if ($lastType === 'array') {
                $options = $this->prepareOptions(array_pop($arguments));

                return array_merge($arguments, $options);
            }
        }

        return $arguments;
    }

    /**
     * Returns a list of options and modifiers compatible with Redis.
     *
     * @param array $options List of options.
     *
     * @return array
     */
    protected function prepareOptions($options)
    {
        $opts = array_change_key_case($options, CASE_UPPER);
        $finalizedOpts = [];

        if (!empty($opts['WITHSCORES'])) {
            $finalizedOpts[] = 'WITHSCORES';
        }

        return $finalizedOpts;
    }

    /**
     * Checks for the presence of the WITHSCORES modifier.
     *
     * @return bool
     */
    protected function withScores()
    {
        $arguments = $this->getArguments();

        if (count($arguments) < 4) {
            return false;
        }

        if (is_string($arguments[3])) {
            return strtoupper($arguments[3]) === 'WITHSCORES';
        }
        return (bool)$arguments[3];
    }
}