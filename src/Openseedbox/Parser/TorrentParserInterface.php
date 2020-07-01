<?php

namespace Openseedbox\Parser;

interface TorrentParserInterface
{

    /**
     * Create a data structure from torrent metadata
     *
     * @param string $data The torrent metadata
     * @return Openseedbox\Parser\TorrentInterface
     */
    public function parse($data);

}