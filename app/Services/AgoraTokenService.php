<?php

namespace App\Services;

/**
 * Agora RTC AccessToken v1 builder.
 *
 * Implements the official Agora token format used by Agora Web SDK v4.
 * Reference: https://github.com/AgoraIO/Tools/tree/master/DynamicKey/AgoraDynamicKey/php
 *
 * Token format: "006" + appId + base64(crcChannel + crcUid + content_len + sig + message)
 * where:
 *   message   = pack(salt) + pack(ts) + pack(privileges)
 *   signature = HMAC-SHA256(appId + channelName + uid + message, certificate)
 *   content   = pack(sig_len) + sig + message
 */
class AgoraTokenService
{
    // Privilege keys (Agora AccessToken v1 spec)
    const PRIV_JOIN_CHANNEL   = 1;
    const PRIV_PUBLISH_AUDIO  = 2;
    const PRIV_PUBLISH_VIDEO  = 3;
    const PRIV_PUBLISH_DATA   = 4;

    /**
     * Build an Agora RTC token for one channel.
     *
     * @param  string  $channel    Channel name (e.g. "ilmishop-live-5")
     * @param  int     $uid        User ID (0 = Agora assigns a random UID)
     * @param  string  $role       'host' or 'audience'
     * @param  int     $expireSec  Token TTL in seconds (default 1 hour)
     * @return string|null         Token string, or null if App ID not configured
     */
    public function buildRtcToken(
        string $channel,
        int    $uid       = 0,
        string $role      = 'audience',
        int    $expireSec = 3600
    ): ?string {
        $appId = config('services.agora.app_id', '');
        $cert  = config('services.agora.certificate', '');

        // If App ID is empty, Agora SDK can't connect at all
        if (empty($appId)) {
            return null;
        }

        // If certificate is empty → "testing mode".
        // The frontend passes null to client.join() and Agora accepts it
        // when "App Certificate" is disabled in the Agora Console.
        if (empty($cert)) {
            return null;
        }

        $ts     = time();
        $salt   = random_int(1, 0x7fffffff);
        $expire = $ts + $expireSec;
        $uidStr = $uid === 0 ? '' : (string) $uid;

        // Build privilege map
        $privileges = [self::PRIV_JOIN_CHANNEL => $expire];
        if ($role === 'host') {
            $privileges[self::PRIV_PUBLISH_AUDIO] = $expire;
            $privileges[self::PRIV_PUBLISH_VIDEO] = $expire;
            $privileges[self::PRIV_PUBLISH_DATA]  = $expire;
        }

        // Pack privileges: uint16(count) then [uint16(key) + uint32(val)] per entry
        $privPack = pack('v', count($privileges));
        foreach ($privileges as $key => $val) {
            $privPack .= pack('v', $key) . pack('V', $val);
        }

        // ✅ FIX: message = salt + ts + privileges (NOT appId + ts + salt + channel...)
        $message = pack('V', $salt) . pack('V', $ts) . $privPack;

        // ✅ FIX: correct signature = HMAC-SHA256(appId + channelName + uid + message, cert)
        $signature = hash_hmac('sha256', $appId . $channel . $uidStr . $message, $cert, true);

        // ✅ FIX: content = sig_len + sig + message (NOT sig at the end)
        $content = pack('v', strlen($signature)) . $signature . $message;

        // Header: crc32(channelName) + crc32(uid) + content_length
        $crcChannel = crc32($channel) & 0xffffffff;
        $crcUid     = crc32($uidStr)  & 0xffffffff;
        $header = pack('V', $crcChannel)
                . pack('V', $crcUid)
                . pack('v', strlen($content));

        return '006' . $appId . base64_encode($header . $content);
    }

    /**
     * Canonical Agora channel name for a live stream.
     */
    public static function channelName(int $liveId): string
    {
        return 'ilmishop-live-' . $liveId;
    }
}