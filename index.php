<?php

if (empty($argv[1])) {
	//https://music.yandex.ru/users/{owner}/playlists/{kinds}
	$uriRaw = 'https://music.yandex.ru/users/vadim.loxx/playlists/1073';
} else {
	$uriRaw = $argv[1];
}

$uriRaw = explode('/', $uriRaw);

$owner = $uriRaw[4];
$kinds = $uriRaw[6];

$uri = 'https://music.yandex.ru/handlers/playlist.jsx?owner=' . $owner . '&kinds=' . $kinds;

$responseRaw = file_get_contents($uri);
$response = json_decode($responseRaw);

$playlistTitle = $response->playlist->title;
$tracks = $response->playlist->tracks;

file_exists($playlistTitle . '.txt') ? unlink($playlistTitle . '.txt') : null;

// Генерация с нумерацией или без нее
$flagNums = true;
$numSong = 1;

foreach ($tracks as $track) {

    $artistsNames = [];
    foreach ($track->artists as $artist) {
        $artistsNames[] = $artist->name;
    }

    $artistsNames = implode(', ', $artistsNames);

    $flagNums ? $fullTrack = $numSong++ . ') ' . $artistsNames . ' - ' . $track->title . PHP_EOL :
        $fullTrack = $artistsNames . ' - ' . $track->title . PHP_EOL;

    file_put_contents($playlistTitle . '.txt', $fullTrack, FILE_APPEND);
}

echo 'Done!';
echo '<br>';
echo '<a href="' . $playlistTitle . '.txt">Список песен</a>';
