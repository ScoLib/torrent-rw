<?php

namespace Openseedbox\Parser;

class Magnet implements TorrentInterface
{

    /**
     * @var string
     */
    private $magnet_uri;
    /**
     * @var array
     */
    private $parsed;

    public function create($hash, $name = null, $trackers = [])
    {
        $magnet = "magnet:?xt=urn:btih:$hash";
        if ($name) {
            $magnet .= "&dn=" . urlencode($name);
        }
        $tracker_part = "";
        if (count($trackers) == 0) {
            $trackers = $this->getDefaultTrackers();
        }
        foreach ($trackers as $tracker) {
            $tracker_part .= "&tr=" . urlencode($tracker);
        }
        return "{$magnet}{$tracker_part}";
    }

    public function parse($magnet_uri)
    {
        $magnet_uri = trim($magnet_uri);
        if (!$this->isValidUri($magnet_uri)) {
            throw new ParseException("Invalid magnet uri: $magnet_uri");
        }
        $this->magnet_uri = $magnet_uri;
        $this->parsed     = [];
        $for_parse        = str_replace("magnet:?", "",
            $magnet_uri); //trim off the first part so the rest can be parsed as a querystring
        $for_parse        = str_replace("&tr=", "&tr[]=",
            $for_parse); //convert &tr= to &tr[]= so that parse_str returns a populated array with an element for each recurring tr= param instead of flattening them
        parse_str($for_parse, $this->parsed);
        if (!$this->isValidHash()) {
            throw new ParseException("Invalid info_hash: {$this->getInfoHash()}");
        }
        return $this;
    }

    public function getInfoHash()
    {
        $xt = $this->parsed["xt"];
        return str_replace("urn:btih:", "", $xt);
    }

    public function getName()
    {
        $dn = @$this->parsed["dn"];
        if (!$dn) {
            return $this->getInfoHash();
        }
        return $dn;
    }

    public function getTrackerUrls()
    {
        return $this->parsed["tr"];
    }

    public function isFromMagnet()
    {
        return true;
    }

    public function getBase64Metadata()
    {
        return null;
    }

    public function getTotalSizeBytes()
    {
        return null;
    }

    public function getMagnetUri()
    {
        return $this->magnet_uri;
    }

    private function isValidUri($magnet_uri)
    {
        $starts_with = "magnet:";
        return strpos($magnet_uri, $starts_with) === 0;
    }

    private function isValidHash()
    {
        return preg_match("/[a-fA-F0-9]{40}/", $this->getInfoHash());
    }

    private function getDefaultTrackers()
    {
        return [
            "udp://tracker.openbittorrent.com:80",
            "udp://tracker.publicbt.com:80",
            "udp://tracker.istole.it:6969",
            "udp://tracker.ccc.de:80",
            "udp://open.demonii.com:1337",
        ];
    }
}