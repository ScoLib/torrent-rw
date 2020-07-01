<?php

namespace Openseedbox\Parser\MagnetParserInterface;

interface MagnetParserInterface
{

    /**
     * Create a magnet URI based on the supplied parameters
     *
     * @param string $hash The info_hash
     * @param string $name The display name
     * @param array $trackers An array of tracker urls
     */
    public function create($hash, $name = null, $trackers = []);

    /**
     * Parse a magnet URI and return a data structure
     *
     * @param string $magnet_uri The magnet uri to parse
     * @return Openseedbox\Parser\TorrentInterface a parsed torrent
     */
    public function parse($magnet_uri);
}