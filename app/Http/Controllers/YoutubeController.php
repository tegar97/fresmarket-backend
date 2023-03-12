<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_YouTube;
class YoutubeController extends Controller
{
    public function searchVideo($query)
    {
        // Inisialisasi Google_Client
        $client = new Google_Client();
        $client->setApplicationName('Laravel Youtube Search');
        $client->setDeveloperKey(env('GOOGLE_DEVELOPER_KEY')); // Masukkan API Key Anda

        // Inisialisasi Google_Service_YouTube
        $youtube = new Google_Service_YouTube($client);

        // Buat objek search list
        $searchResponse = $youtube->search->listSearch('id,snippet', array(
            'q' => $query,
            'maxResults' => 1, // Jumlah video yang ingin ditampilkan
            'type' => 'video',
            'order' => 'relevance', // Urutkan berdasarkan relevansi
        ));

        // Tampilkan hasil pencarian
        foreach ($searchResponse['items'] as $searchResult) {
            $videoId = $searchResult['id']['videoId'];
            $title = $searchResult['snippet']['title'];
            $description = $searchResult['snippet']['description'];
            $thumbnail = $searchResult['snippet']['thumbnails']['default']['url'];
            $publishedAt = $searchResult['snippet']['publishedAt'];
            $channelId = $searchResult['snippet']['channelId'];
            $channelTitle = $searchResult['snippet']['channelTitle'];

            // Tampilkan informasi video yang ditemukan
            dd($videoId, $title, $description, $thumbnail, $publishedAt, $channelId, $channelTitle  );
        }
    }
}
