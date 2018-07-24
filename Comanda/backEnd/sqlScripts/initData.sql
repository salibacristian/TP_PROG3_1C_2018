

INSERT INTO `user`
(`name`, `email`, `password`, `isSuspended`,`isDeleted`,`sectorId`,`role`) 
VALUES

 ('Cristian Saliba','chriseze21@gmail.com','a1234',0,0,null, 1)
,('Gabriel Saliba','gaby09@gmail.com','a1234',0,0,null, 1)
,('Ailen Aceval','ailu02@gmail.com','a1234',0,0,null, 1)


,('Ricardo Saliba','ricjsalib@yahoo.com','a1234',0,0,null, 2)
,('Daniela Arzeni','ardadri@yahoo.com','a1234',0,0,null, 2)


,('Alfredo Arzeni','alfredohugo@hotmail.com','a1234',0,0,2, 3)
,('Luis Aceval','luchoaceval@gmail.com','a1234',0,0,2, 3)

,('Julian Aceval','julia@gmail.com','a1234',0,0,1, 3)
,('Cristina Caparroz','criscaparroz@gmail.com','a1234',0,0,1, 3)

,('Noelia Sosa','noelialole@gmail.com','a1234',0,0,0, 3)
,('Patata Potes','bpda@gmail.com','a1234',0,0,3, 3)

INSERT INTO `restaurantTable`
(`code`,`status`)
VALUES
('00000',0)
,('00001',0)
,('00002',0)
,('00003',0)
,('00004',0)
,('00005',0)
,('00006',0)
,('00007',0)
,('00008',0)
,('00009',0)
,('00010',0)

INSERT INTO `item`
(`name`, `sectorId`, `estimatedTime`, `amount`) 
VALUES
('Coca Cola', 0, null, 50)
,('Sprite', 0, null, 50)
,('Rutini', 0, null, 650)
,('Catena Zapata', 0, null, 600)
,('White Horse', 0, null, 499)

,('Cerveza roja', 1, null, 100)
,('Cerveza rubia', 1, null, 100)
,('Cerveza negra', 1, null, 100)

,('Pizza', 2, 10, 200)
,('Empanada', 2, 10, 30)
,('Pollo con guarnicion', 2, 15, 200)
,('Vacio con guarnicion', 2, 15, 250)
,('Cappelletti', 2, 12, 230)
,('Espagueti', 2, 12, 210)

,('Porcion de torta', 3, null, 60)
,('Flan', 3, null, 40)
,('Bocha helada', 3, null, 40)

