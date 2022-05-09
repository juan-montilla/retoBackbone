# retoBackbone
reto Backbone in order to hire me

Project log:

- See how the response could be adapted to models and relationships.
- Downloaded the files from The Mexican Postal Service. Decided to go with the txt format
- Ugly as expected with the wrong charset. Loaded it in libreoffice-calc to beautify it a little bit. Converted to CSV file
- Checked which charset is in use. Used iconv to transform from charset=iso-8859-1 to utf8 
- A little bit of reverse engineering . Checked which rows can be read as rows and/or tables. 
- Created different migrations for each model (everything was living in paper)
- Created the models
- Created ZipCode{Controller, StoreRequest, UpdateRequest}
- Now it is time to test code.. so i need an env to work with. Decided to go with laradock.
- Setting up laradock.. mysql db, user; changed php version to 8.1



