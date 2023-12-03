<?php
require __DIR__ . '/PHP-Source-Query-master/SourceQuery/bootstrap.php';

use xPaw\SourceQuery\SourceQuery;

$serverIP = '82.208.17.109';
$serverPort = 27712;

$Query = new SourceQuery();
try {
    $Query->Connect($serverIP, $serverPort, 1, SourceQuery::SOURCE);
    $serverInfo = $Query->GetInfo();
    $players = $Query->GetPlayers();
    $ping = $Query->Ping();
    $status = ($ping !== false) ? '<span class="status-on">ONLINE</span>' : '<span class="status-off">OFFLINE</span>';
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}
$Query->Disconnect();

// Získání informací o Discord serveru pomocí Discord API
$discordEndpoint = 'https://discord.com/api/v10/guilds/{GUILD_ID}';
$guildId = '800146380190384148';
$discordToken = 'YOUR_DISCORD_API_TOKEN';

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => str_replace('{GUILD_ID}', $guildId, $discordEndpoint),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bot ' . $discordToken,
        'Content-Type: application/json'
    ]
]);
$response = curl_exec($curl);
curl_close($curl);

$discordInfo = json_decode($response, true);
$discordOnlineMembers = $discordInfo['approximate_presence_count'];
$totalDiscordMembers = $discordInfo['approximate_member_count'];
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Server Info</title>
    <link rel="stylesheet" href="style.css">
    <script>
      function showPlayers() {
        var playerNames = <?php echo json_encode($players); ?>;
        var playerList = document.getElementById('player-list');
        playerList.innerHTML = playerNames.join(', ');
      }
    </script>
  </head>
  <body>
    <table class="server-info-table">
      <tr>
        <th>System</th>
        <th>Server</th>
        <th>IP Adresa</th>
        <th>Mapa</th>
        <th>Hráči</th>
        <th>Stav</th>
      </tr>
      <tr>
        <td><img src="obrazky/csgo_ikonka.png" alt="Icon" class="icon"></td>
        <td><?php echo $serverInfo['HostName']; ?></td>
        <td><?php echo $serverIP . ':' . $serverPort; ?></td>
        <td><?php echo $serverInfo['Map']; ?></td>
        <td onmouseover="showPlayers()"><?php echo count($players) . '/' . $serverInfo['MaxPlayers']; ?></td>
        <td><?php echo $status; ?></td>
      </tr>
      <tr>
        <td><img src="obrazky/discord_ikonka.png" alt="Icon" class="icon"></td>
        <td>FiredToGame.cz Discord</td>
        <td><a href="https://discord.gg/aP6YVF2cWE" target="_blank">https://discord.gg/aP6YVF2cWE</a></td>
        <td>Discord</td>
        <td><?php echo $discordOnlineMembers . '/' . $totalDiscordMembers; ?></td>
        <td style="color: #00c853; font-weight: bold;">ONLINE</td>
      </tr>
    </table>

    <div id="player-list"></div>

    <script>
      function showPlayers() {
        var playerNames = <?php echo json_encode($players); ?>;
        var playerList = document.getElementById('player-list');
        playerList.innerHTML = playerNames.join(', ');
      }
    </script>
  </body>
</html>
