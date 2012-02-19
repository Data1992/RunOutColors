/* UPDATE SCRIPT 190212 */
DROP TABLE IF EXISTS static;
DROP TABLE IF EXISTS static_version;

CREATE TABLE blog_post_attachment (
  id serial NOT NULL,
  post integer NOT NULL,
  image integer NOT NULL,
  CONSTRAINT blog_post_attachment_pkey PRIMARY KEY (id )
) WITH ( OIDS=FALSE );
ALTER TABLE blog_post_attachment OWNER TO roc;

ALTER TABLE blog_post ADD COLUMN front_image integer;

ALTER TABLE blog_post ADD CONSTRAINT "FK_blog_post_gallery_image" FOREIGN KEY (front_image) REFERENCES gallery_image (id)
  ON UPDATE NO ACTION ON DELETE NO ACTION;
CREATE INDEX "FKI_blog_post_gallery_image" ON blog_post(front_image);
