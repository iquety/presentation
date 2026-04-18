<?php

declare(strict_types=1);

namespace Tests;

trait HasPermissionProvidersTrait
{
    /** @return array<string,array<mixed>> */
    public function canProvider(): array
    {
        $list = [];

        $list['bool hi']              = ['show-hi', true, 'show-bye', false, 'I can say hi and '];
        $list['bool bye']             = ['show-hi', false, 'show-bye', true, ' and I can say bye'];
        $list['bool hi and bool bye'] = ['show-hi', true, 'show-bye', true, 'I can say hi and I can say bye'];
        $list['false both']           = ['show-hi', false, 'show-bye', false, ' and '];

        $list['string hi']                = ['show-hi', 'true', 'show-bye', 'false', 'I can say hi and '];
        $list['string bye']               = ['show-hi', 'false', 'show-bye', 'true', ' and I can say bye'];
        $list['string hi and string bye'] = ['show-hi', 'true', 'show-bye', 'true', 'I can say hi and I can say bye'];
        $list['string false both']        = ['show-hi', 'false', 'show-bye', 'false', ' and '];

        $list['int hi']             = ['show-hi', 1, 'show-bye', 0, 'I can say hi and '];
        $list['int bye']            = ['show-hi', 0, 'show-bye', 1, ' and I can say bye'];
        $list['int hi and int bye'] = ['show-hi', 1, 'show-bye', 1, 'I can say hi and I can say bye'];
        $list['int false both']     = ['show-hi', 0, 'show-bye', 0, ' and '];

        $list['numeric hi']                 = ['show-hi', '1', 'show-bye', '0', 'I can say hi and '];
        $list['numeric bye']                = ['show-hi', '0', 'show-bye', '1', ' and I can say bye'];
        $list['numeric hi and numeric bye'] = ['show-hi', '1', 'show-bye', '1', 'I can say hi and I can say bye'];
        $list['numeric false both']         = ['show-hi', '0', 'show-bye', '0', ' and '];

        $list['without permissions'] = ['', '', '', '', ' and '];

        return $list;
    }

    /** @return array<string,array<mixed>> */
    public function cannotProvider(): array
    {
        $list = [];

        $list['bool hi']              = ['show-hi', false, 'show-bye', true, "I can't say hi and "];
        $list['bool bye']             = ['show-hi', true, 'show-bye', false, " and I can't say bye"];
        $list['bool hi and bool bye'] = ['show-hi', false, 'show-bye', false, "I can't say hi and I can't say bye"];
        $list['false both']           = ['show-hi', true, 'show-bye', true, ' and '];

        $list['string hi']                = ['show-hi', 'false', 'show-bye', 'true', "I can't say hi and "];
        $list['string bye']               = ['show-hi', 'true', 'show-bye', 'false', " and I can't say bye"];
        $list['string hi and string bye'] = ['show-hi', 'false', 'show-bye', 'false', "I can't say hi and I can't say bye"];
        $list['string false both']        = ['show-hi', 'true', 'show-bye', 'true', ' and '];

        $list['int hi']             = ['show-hi', 0, 'show-bye', 1, "I can't say hi and "];
        $list['int bye']            = ['show-hi', 1, 'show-bye', 0, " and I can't say bye"];
        $list['int hi and int bye'] = ['show-hi', 0, 'show-bye', 0, "I can't say hi and I can't say bye"];
        $list['int false both']     = ['show-hi', 1, 'show-bye', 1, ' and '];

        $list['numeric hi']                 = ['show-hi', '0', 'show-bye', '1', "I can't say hi and "];
        $list['numeric bye']                = ['show-hi', '1', 'show-bye', '0', " and I can't say bye"];
        $list['numeric hi and numeric bye'] = ['show-hi', '0', 'show-bye', '0', "I can't say hi and I can't say bye"];
        $list['numeric false both']         = ['show-hi', '1', 'show-bye', '1', ' and '];

        $list['without permissions'] = ['', '', '', '', ' and '];

        return $list;
    }

    /** @return array<string,array<mixed>> */
    public function canElseProvider(): array
    {
        $list = [];

        $list['bool hi']              = ['show-hi', true, 'show-bye', false, "I can say hi and I can't say bye"];
        $list['bool bye']             = ['show-hi', false, 'show-bye', true, "I can't say hi and I can say bye"];
        $list['bool hi and bool bye'] = ['show-hi', true, 'show-bye', true, 'I can say hi and I can say bye'];
        $list['false both']           = ['show-hi', false, 'show-bye', false, "I can't say hi and I can't say bye"];

        $list['string hi']                = ['show-hi', 'true', 'show-bye', 'false', "I can say hi and I can't say bye"];
        $list['string bye']               = ['show-hi', 'false', 'show-bye', 'true', "I can't say hi and I can say bye"];
        $list['string hi and string bye'] = ['show-hi', 'true', 'show-bye', 'true', 'I can say hi and I can say bye'];
        $list['string false both']        = ['show-hi', 'false', 'show-bye', 'false', "I can't say hi and I can't say bye"];

        $list['int hi']             = ['show-hi', 1, 'show-bye', 0, "I can say hi and I can't say bye"];
        $list['int bye']            = ['show-hi', 0, 'show-bye', 1, "I can't say hi and I can say bye"];
        $list['int hi and int bye'] = ['show-hi', 1, 'show-bye', 1, 'I can say hi and I can say bye'];
        $list['int false both']     = ['show-hi', 0, 'show-bye', 0, "I can't say hi and I can't say bye"];

        $list['numeric hi']                 = ['show-hi', '1', 'show-bye', '0', "I can say hi and I can't say bye"];
        $list['numeric bye']                = ['show-hi', '0', 'show-bye', '1', "I can't say hi and I can say bye"];
        $list['numeric hi and numeric bye'] = ['show-hi', '1', 'show-bye', '1', 'I can say hi and I can say bye'];
        $list['numeric false both']         = ['show-hi', '0', 'show-bye', '0', "I can't say hi and I can't say bye"];

        $list['without permissions'] = ['', '', '', '', "I can't say hi and I can't say bye"];

        return $list;
    }

    /** @return array<string,array<mixed>> */
    public function cannotElseProvider(): array
    {
        $list = [];

        $list['bool hi']              = ['show-hi', false, 'show-bye', true, "I can't say hi and I can say bye"];
        $list['bool bye']             = ['show-hi', true, 'show-bye', false, "I can say hi and I can't say bye"];
        $list['bool hi and bool bye'] = ['show-hi', false, 'show-bye', false, "I can't say hi and I can't say bye"];
        $list['false both']           = ['show-hi', true, 'show-bye', true, 'I can say hi and I can say bye'];

        $list['string bool hi']              = ['show-hi', 'false', 'show-bye', 'true', "I can't say hi and I can say bye"];
        $list['string bool bye']             = ['show-hi', 'true', 'show-bye', 'false', "I can say hi and I can't say bye"];
        $list['string bool hi and bool bye'] = ['show-hi', 'false', 'show-bye', 'false', "I can't say hi and I can't say bye"];
        $list['string false both']           = ['show-hi', 'true', 'show-bye', 'true', 'I can say hi and I can say bye'];

        $list['int bool hi']              = ['show-hi', 0, 'show-bye', 1, "I can't say hi and I can say bye"];
        $list['int bool bye']             = ['show-hi', 1, 'show-bye', 0, "I can say hi and I can't say bye"];
        $list['int bool hi and bool bye'] = ['show-hi', 0, 'show-bye', 0, "I can't say hi and I can't say bye"];
        $list['int false both']           = ['show-hi', 1, 'show-bye', 1, 'I can say hi and I can say bye'];

        $list['string int bool hi']              = ['show-hi', '0', 'show-bye', '1', "I can't say hi and I can say bye"];
        $list['string int bool bye']             = ['show-hi', '1', 'show-bye', '0', "I can say hi and I can't say bye"];
        $list['string int bool hi and bool bye'] = ['show-hi', '0', 'show-bye', '0', "I can't say hi and I can't say bye"];
        $list['string int false both']           = ['show-hi', '1', 'show-bye', '1', 'I can say hi and I can say bye'];

        $list['without permissions'] = ['', '', '', '', 'I can say hi and I can say bye'];

        return $list;
    }
}
