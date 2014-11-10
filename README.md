
This repo allows users to see which of their Steam games will work natively on Linux and Mac

It uses Steam's public API to retrieve a JSON of their game list and retrieve JSON of each game's attributes. It filters the results into two categories based on the "Linux" and "Mac" attributes. JSON -> App ID -> data -> platforms -> (Linux | Mac | Windows)

To use the public API to gather a user's library information, you must register your domain with Steam to receive a key

This key is stored in a file called CONFIG (not included in this repo) under the variable $steamkey

URL for user's library. My Steam API key is removed. Get your own. Steam ID (account ID) is a generic example provided by Valve in the API webpages
http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=XXXX&steamid=76561197960434622&format=json

URL for Individual Game Info (App ID 10 is Counter-Strike)
http://store.steampowered.com/api/appdetails?appids=10

Enjoy!
- DefenTheNation
