# retoBackbone
reto Backbone

Project log:

- See how the response could be adapted to models and relationships.
- I went to The Mexican Postal Service website. Decided to go with the txt format
- Ugly as expected with the wrong charset. Loaded it in libreoffice-calc to beautify it a little bit. Converted to CSV file
- Checked which charset is in use. Used iconv to transform from charset=iso-8859-1 to utf8 
- A little bit of reverse engineering . Checked which rows can be read as rows and/or tables. 
- Created different migrations for each model (everything was living in paper)
- Created the models
- Created ZipCode{Controller, StoreRequest, UpdateRequest}
- Now it is time to test code.. so i need an env to work with. Decided to go with laradock.
- Setting up laradock.. mysql db, user; changed php version to 8.1
- Removed laradock; for some reason the mysql container does not want to work...
- Used only a simple mysql container.
- Using the response as template; i modelled each array row to easily create each model and its relationshios
- Import process works; a bit slow tho. Got an issue with the last two lines to be imported Zip code = 99998
- Fixed previous issue.. an empty line :-|
- Modified the input file to give me a space if a field is empty (|| becomes |0x20|)
- Issues with many-to-many relationship.. laravel *wants* to use zip_code_id instead of zipcode_id



