# lightbot-haan-gr
This is an attempt to translate lightbot (github haan version) to Greek for elementary students.

lightbot-editor: Levels are actually rotated in the main game...



Main game:
-200225 google fonts offline loading
changes 190210
- maps_loops_only1.txt added
changes 190205 
- maps are hardcoded in html.No server required (eg http://.../lightbot_haan/index.html?map=maps_d1.txt)



Files:

index_easy.html : play easy levels
select.php : select which map to play (maps are stored at maps folder)

maps/map_create.php : create random maps
create_many_maps.php :create many random maps
create_many_maps4x4.php : create many small maps 4x4
create_many_maps4x4loop.php : (not finished) create many loop maps


Sample maps:

index.html?maps=maps.txt
index.html?maps=maps_nax.txt
index.html?maps=maps_easy.txt
index.html?maps=maps_d1.txt
index.html?maps=maps_random_2(4x4_3lights).txt
index.html?maps=maps_random_1(4x4_3lights).txt
index.html?map=maps_loops_only1.txt
index.html?maps=maps_random_2(5lights).txt
index.html?maps=maps_random_3(5lights).txt