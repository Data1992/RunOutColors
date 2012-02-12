/* UPDATE SCRIPT 120212 */
ALTER TABLE gallery_image ADD COLUMN downloadable boolean NOT NULL DEFAULT FALSE;
ALTER TABLE gallery_category ADD COLUMN download_directory character varying(150) NOT NULL DEFAULT '';
