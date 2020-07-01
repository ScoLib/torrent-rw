<?php

use Openseedbox\Parser\Magnet;

class MagnetTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var \Openseedbox\Parser\Magnet
     */
    private $parser;
    /**
     * @var \Openseedbox\Parser\Magnet
     */
    private $magnet;

    protected function setUp(): void
    {
        $this->parser = new Magnet();
        $this->magnet = $this->parser->parse($this->getTestMagnet());
    }

    public function testIsFromMagnet()
    {
        $this->assertTrue($this->magnet->isFromMagnet());
    }

    public function testGetInfoHash()
    {
        $this->assertEquals("07a9de9750158471c3302e4e95edb1107f980fa6", $this->magnet->getInfoHash());
    }

    public function testGetName()
    {
        $this->assertEquals("Pioneer One S01E01 720p x264 VODO", $this->magnet->getName());
    }

    public function testGetMagnetUri()
    {
        $this->assertEquals($this->getTestMagnet(), $this->magnet->getMagnetUri());
    }

    public function testGetTrackerUrls()
    {
        $trackers = $this->magnet->getTrackerUrls();
        $this->assertCount(5, $trackers);
        $this->assertEquals("udp://tracker.openbittorrent.com:80", $trackers[0]);
    }

    public function testRejectsInvalidMagnetLink()
    {
        $invalidLink = "http://google.co.nz";
        $this->expectException("Openseedbox\Parser\ParseException");
        $test = $this->magnet->parse($invalidLink);
    }

    public function testCreate()
    {
        $created = $this->magnet->create("07a9de9750158471c3302e4e95edb1107f980657", "Test name");

        $this->assertEquals("magnet:?xt=urn:btih:07a9de9750158471c3302e4e95edb1107f980657&dn=Test+name&tr=udp%3A%2F%2Ftracker.openbittorrent.com%3A80&tr=udp%3A%2F%2Ftracker.publicbt.com%3A80&tr=udp%3A%2F%2Ftracker.istole.it%3A6969&tr=udp%3A%2F%2Ftracker.ccc.de%3A80&tr=udp%3A%2F%2Fopen.demonii.com%3A1337",
            $created);
        $parsed = $this->magnet->parse($created);

        $this->assertEquals("07a9de9750158471c3302e4e95edb1107f980657", $parsed->getInfoHash());
        $this->assertEquals("Test name", $parsed->getName());

        $this->assertCount(5, $parsed->getTrackerUrls());
    }

    public function testCreateWithTrackers()
    {
        $created = $this->magnet->create("07a9de9750158471c3302e4e95edb1107f980657", "Test name",
            ["http://tracker1.com:80", "http://tracker2.com:8080"]);
        $parsed  = $this->magnet->parse($created);

        $trackers = $parsed->getTrackerUrls();
        $this->assertCount(2, $trackers);
        $this->assertEquals("http://tracker1.com:80", $trackers[0]);
        $this->assertEquals("http://tracker2.com:8080", $trackers[1]);
    }

    public function testGetNameOnMagnetWithoutDNReturnsHash()
    {
        $hash   = "07a9de9750158471c3302e4e95edb1107f980657";
        $magnet = $this->parser->parse("magnet:?xt=urn:btih:{$hash}");
        $this->assertEquals($hash, $magnet->getName());
    }

    private function getTestMagnet()
    {
        return "magnet:?xt=urn:btih:07a9de9750158471c3302e4e95edb1107f980fa6&dn=Pioneer+One+S01E01+720p+x264+VODO&tr=udp%3A%2F%2Ftracker.openbittorrent.com%3A80&tr=udp%3A%2F%2Ftracker.publicbt.com%3A80&tr=udp%3A%2F%2Ftracker.istole.it%3A6969&tr=udp%3A%2F%2Ftracker.ccc.de%3A80&tr=udp%3A%2F%2Fopen.demonii.com%3A1337";
    }

}