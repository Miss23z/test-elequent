<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Треки — MVP</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; background: #f5f5f5; }
        h1 { margin-bottom: 24px; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.1); }
        th, td { padding: 10px 14px; text-align: left; font-size: 14px; }
        th { background: #1a1a2e; color: #fff; font-weight: 600; }
        tr:nth-child(even) { background: #f9f9f9; }
        td { border-bottom: 1px solid #eee; vertical-align: top; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 12px; margin: 1px 2px; }
        .badge-artist { background: #e3f2fd; color: #1565c0; }
        .badge-album { background: #e8f5e9; color: #2e7d32; }
        .badge-genre { background: #fff3e0; color: #e65100; }
        .badge-active { background: #e8f5e9; color: #2e7d32; }
        .badge-inactive { background: #ffebee; color: #c62828; }
        .age { font-weight: 600; }
        .duration { color: #666; font-size: 13px; }
        .pagination { margin-top: 20px; display: flex; gap: 8px; }
        .pagination a, .pagination span { padding: 6px 12px; border-radius: 4px; text-decoration: none; color: #333; background: #fff; border: 1px solid #ddd; }
        .pagination .active { background: #1a1a2e; color: #fff; border-color: #1a1a2e; }
        .lyrics-toggle { cursor: pointer; color: #1565c0; font-size: 13px; }
        .lyrics-text { display: none; margin-top: 6px; padding: 8px; background: #f5f5f5; border-radius: 4px; font-size: 13px; white-space: pre-wrap; max-width: 300px; }
    </style>
</head>
<body>
    <h1>Треки</h1>

    <table>
        <thead>
            <tr>
                <th>Название</th>
                <th>Артисты</th>
                <th>Альбомы</th>
                <th>Жанры</th>
                <th>Длит.</th>
                <th>Возраст</th>
                <th>Прослуш.</th>
                <th>Правообл.</th>
                <th>Активен</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tracks as $track)
            <tr>
                <td>
                    <strong>{{ $track->title }}</strong>
                    @if ($track->lyric)
                        <br><span class="lyrics-toggle" onclick="this.nextElementSibling.style.display = this.nextElementSibling.style.display === 'block' ? 'none' : 'block'">Показать текст</span>
                        <div class="lyrics-text">{{ $track->lyric }}</div>
                    @endif
                </td>
                <td>
                    @foreach ($track->artists as $artist)
                        <span class="badge badge-artist">{{ $artist->name }}</span>
                    @endforeach
                </td>
                <td>
                    @foreach ($track->albums as $album)
                        <span class="badge badge-album">{{ $album->title }}</span>
                    @endforeach
                </td>
                <td>
                    @foreach ($track->genres as $genre)
                        <span class="badge badge-genre">{{ $genre->title }}</span>
                    @endforeach
                </td>
                <td class="duration">{{ round($track->duration / 1000, 1) }}с</td>
                <td class="age">{{ $track->age_rating->value }}</td>
                <td>{{ number_format($track->play_count, 0, ',', ' ') }}</td>
                <td>{{ $track->copyrightHolder?->name }}</td>
                <td>
                    @if ($track->is_active)
                        <span class="badge badge-active">Да</span>
                    @else
                        <span class="badge badge-inactive">Нет</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if ($tracks->hasPages())
    <div class="pagination">
        @if ($tracks->onFirstPage())
            <span>&laquo;</span>
        @else
            <a href="{{ $tracks->previousPageUrl() }}">&laquo;</a>
        @endif

        @foreach ($tracks->getUrlRange(1, $tracks->lastPage()) as $page => $url)
            @if ($page == $tracks->currentPage())
                <span class="active">{{ $page }}</span>
            @else
                <a href="{{ $url }}">{{ $page }}</a>
            @endif
        @endforeach

        @if ($tracks->hasMorePages())
            <a href="{{ $tracks->nextPageUrl() }}">&raquo;</a>
        @else
            <span>&raquo;</span>
        @endif
    </div>
    @endif
</body>
</html>
