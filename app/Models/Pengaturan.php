<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Ambil nilai pengaturan berdasarkan key.
     */
    public static function get(string $key, $default = null)
    {
        return static::where('key', $key)->value('value') ?? $default;
    }

    /**
     * Simpan / update nilai pengaturan berdasarkan key.
     */
    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Simpan daftar link video Display secara berurutan.
     * Disimpan sebagai JSON array agar mendukung lebih dari 1 video
     * beserta urutan tampilnya (urutan array = urutan tayang).
     */
    public static function setDisplayVideoLinks(array $links): void
    {
        $links = collect($links)
            ->map(fn ($v) => trim((string) $v))
            ->filter(fn ($v) => $v !== '')
            ->values()
            ->all();

        static::set('display_link_video', !empty($links) ? json_encode($links) : null);
    }

    /**
     * Ambil daftar link video Display apa adanya (belum dikonversi ke embed),
     * sudah terurut sesuai urutan yang diatur admin. Tetap kompatibel dengan
     * data lama yang tersimpan sebagai satu URL string biasa (bukan JSON).
     */
    public static function displayVideoLinks(): array
    {
        $raw = static::get('display_link_video');

        if (empty($raw)) {
            return [];
        }

        $decoded = json_decode($raw, true);

        if (is_array($decoded)) {
            return collect($decoded)
                ->map(fn ($v) => trim((string) $v))
                ->filter(fn ($v) => $v !== '')
                ->values()
                ->all();
        }

        // Kompatibilitas data lama (sebelum fitur multi-video): value tersimpan sebagai 1 URL biasa.
        return [$raw];
    }

    /**
     * Ambil daftar link video Display dalam bentuk URL embed siap pakai <iframe>,
     * tetap terurut sesuai urutan yang diatur admin.
     *
     * @param bool $loopSingle Jika true dan hanya ada 1 video YouTube, video akan di-loop terus menerus
     *                         (perilaku lama saat hanya ada 1 video).
     */
    public static function displayVideoEmbeds(bool $loopSingle = false): array
    {
        $links = static::displayVideoLinks();

        return array_map(function ($link) use ($links, $loopSingle) {
            if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|shorts\/))([a-zA-Z0-9_-]{6,})/', $link, $m)) {
                $params = 'autoplay=1&mute=1&controls=1&rel=0';

                if ($loopSingle && count($links) === 1) {
                    $params .= '&loop=1&playlist=' . $m[1];
                }

                return 'https://www.youtube.com/embed/' . $m[1] . '?' . $params;
            }

            // Bukan link YouTube yang dikenali (mis. sudah berupa link embed lain), pakai apa adanya.
            return $link;
        }, $links);
    }
}