@php
  $asString = str_pad($attributes['price'], 6, "0", STR_PAD_LEFT);
  $bronze = substr($asString, -2);
  $silver = substr($asString, -4, 2);
  $gold = substr($asString, 0, -4);
@endphp

<span class="inline-flex items-center">
  @if($gold !== '00')
    <img class="h-4" src="https://render.guildwars2.com/file/090A980A96D39FD36FBB004903644C6DBEFB1FFB/156904.png">
    {{$gold}}
  @endif
  @if($silver !== '00')
    <img class="h-4" src="https://render.guildwars2.com/file/E5A2197D78ECE4AE0349C8B3710D033D22DB0DA6/156907.png">
    {{$silver}}
  @endif
  @if($bronze !== '00')
    <img class="h-4" src="https://render.guildwars2.com/file/6CF8F96A3299CFC75D5CC90617C3C70331A1EF0E/156902.png">
    {{$bronze}}
  @endif
</span>