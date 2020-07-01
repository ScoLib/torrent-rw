<?php

namespace Openseedbox\Parser;

interface TorrentInterface
{

    /**
     * Returns the torrents info_hash
     */
    public function getInfoHash();

    /**
     * Returns the torrents name
     */
    public function getName();

    /**
     * Indicates whether or not the torrent was created from a magnet link or from actual torrent metadata
     * This helps you determine whether or not getMetadataBase64() and getTotalSizeBytes() return anything meaningful
     */
    public function isFromMagnet();

    /**
     * Returns the torrent metadata as a base64 encoded string
     */
    public function getBase64Metadata();

    /**
     * Returns the total size of the torrent once it has been downloaded, in bytes
     */
    public function getTotalSizeBytes();

    /**
     * Returns an array of tracker urls, as found in the torrent or magnet link
     */
    public function getTrackerUrls();

    /**
     * Returns the torrents magnet uri. Will only return something if isFromMagnet() returns true.
     */
    public function getMagnetUri();

}