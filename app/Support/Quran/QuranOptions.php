<?php

declare(strict_types=1);

namespace App\Support\Quran;

use Illuminate\Support\Facades\Cache;
use Jhonoryza\LaravelQuran\Models\Quran;
use Jhonoryza\LaravelQuran\Models\QuranVerse;

class QuranOptions
{
    private const CACHE_VERSION = 'v2';

    /**
     * @return array<int, string>
     */
    public static function surahOptions(): array
    {
        return Cache::remember(
            key: 'quran.surah_options.' . self::CACHE_VERSION,
            ttl: now()->addDay(),
            callback: static fn (): array => Quran::query()
                ->orderBy('external_id')
                ->get()
                ->mapWithKeys(
                    fn (Quran $surah): array => [
                        $surah->external_id => sprintf(
                            '%03d. %s (%s)',
                            $surah->external_id,
                            $surah->latin,
                            $surah->translation,
                        ),
                    ],
                )
                ->toArray(),
        );
    }

    public static function surahLabel(?int $surahId): ?string
    {
        if ($surahId === null) {
            return null;
        }

        return self::surahOptions()[$surahId] ?? Quran::query()
            ->where('external_id', $surahId)
            ->first()
            ?->latin;
    }

    /**
     * @return array<int, string>
     */
    public static function ayahOptions(?int $surahId): array
    {
        if ($surahId === null) {
            return [];
        }

        return Cache::remember(
            key: "quran.surah_{$surahId}_ayah_options." . self::CACHE_VERSION,
            ttl: now()->addHour(),
            callback: static function () use ($surahId): array {
                $surah = Quran::query()
                    ->where('external_id', $surahId)
                    ->first();

                if ($surah === null) {
                    return [];
                }

                return QuranVerse::query()
                    ->where('quran_id', $surah->id)
                    ->orderBy('ayah')
                    ->get()
                    ->mapWithKeys(
                        fn (QuranVerse $verse): array => [
                            $verse->ayah => (string) $verse->ayah,
                        ],
                    )
                    ->toArray();
            },
        );
    }

    public static function maxAyah(?int $surahId): ?int
    {
        if ($surahId === null) {
            return null;
        }

        return Cache::remember(
            key: "quran.surah_{$surahId}_max_ayah." . self::CACHE_VERSION,
            ttl: now()->addHour(),
            callback: static fn (): ?int => Quran::query()
                ->where('external_id', $surahId)
                ->value('num_ayah'),
        );
    }

    public static function ayahArabic(?int $surahId, ?int $ayahNumber): ?string
    {
        if ($surahId === null || $ayahNumber === null) {
            return null;
        }

        return Cache::remember(
            key: "quran.surah_{$surahId}_ayah_{$ayahNumber}_arabic." . self::CACHE_VERSION,
            ttl: now()->addHour(),
            callback: static function () use ($surahId, $ayahNumber): ?string {
                $surah = Quran::query()
                    ->where('external_id', $surahId)
                    ->first();

                if ($surah === null) {
                    return null;
                }

                return QuranVerse::query()
                    ->where('quran_id', $surah->id)
                    ->where('ayah', $ayahNumber)
                    ->value('arabic');
            },
        );
    }
}
