<?php

namespace App\Core\Security;

class IpManager
{
    protected $blacklistFile;
    protected $whitelistFile;

    public function __construct()
    {
        $this->blacklistFile = dirname(__DIR__, 3) . '/storage/security/ip_blacklist.json';
        $this->whitelistFile = dirname(__DIR__, 3) . '/storage/security/ip_whitelist.json';
    }

    public function isBlacklisted($ip)
    {
        $blacklist = $this->getList('blacklist');
        return in_array($ip, $blacklist);
    }

    public function isWhitelisted($ip)
    {
        $whitelist = $this->getList('whitelist');
        return in_array($ip, $whitelist);
    }

    public function addToBlacklist($ip)
    {
        $blacklist = $this->getList('blacklist');
        if (!in_array($ip, $blacklist)) {
            $blacklist[] = $ip;
            $this->saveList('blacklist', $blacklist);
        }
    }

    public function addToWhitelist($ip)
    {
        $whitelist = $this->getList('whitelist');
        if (!in_array($ip, $whitelist)) {
            $whitelist[] = $ip;
            $this->saveList('whitelist', $whitelist);
        }
    }

    protected function getList($type)
    {
        $file = $type === 'blacklist' ? $this->blacklistFile : $this->whitelistFile;
        if (!file_exists($file)) {
            return [];
        }
        return json_decode(file_get_contents($file), true) ?? [];
    }

    protected function saveList($type, array $list)
    {
        $file = $type === 'blacklist' ? $this->blacklistFile : $this->whitelistFile;
        file_put_contents($file, json_encode($list));
    }
}