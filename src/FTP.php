<?php


class FTP
{
    const NB_DOWNLOADED_FILE_LIMIT = 10;
    private $ftp_server;
    private $ftp_user;
    private $ftp_password;
    private $reg_filter;
    private $conn_id;


    /**
     * FTP constructor.
     * @param $ftp_serveur
     * @param $ftp_user
     * @param $ftp_password
     * @param $reg_filter
     */
    public function __construct($ftp_serveur, $ftp_user, $ftp_password, $reg_filter = "")
    {
        $this->ftp_server = $ftp_serveur;
        $this->ftp_user = $ftp_user;
        $this->ftp_password = $ftp_password;
        $this->reg_filter = $reg_filter;
    }

    /**
     *
     */
    public function getFTPFilesFromPromoter()
    {
        $this->ftpConnect();
        $nb_files_downloaded = 1;

        $this->ftp_sync(".",$nb_files_downloaded);    // Use "." if you are in the current directory

        ftp_close($this->conn_id);


    }

    // ftp_sync - Copy directory and file structure

    /**
     * @param $dir
     * @param $nb_files_downloaded
     */
    public function ftp_sync($dir, &$nb_files_downloaded)
    {


        if ($dir !== ".") {
            if (ftp_chdir($this->conn_id, $dir) == false) {
                $this->writeln("<error>Change Dir Failed: $dir<BR></error>");
                return;
            }
            if (!(is_dir($dir)))
                mkdir($dir);
            chdir($dir);
        }

        $ftp_files = ftp_nlist($this->conn_id, ".");

        if ($ftp_files !== false) {
            foreach ($ftp_files as $file) {

                if ($file === '.' || $file === '..' || preg_match("/^\.ftpquota/", $file))
                    continue;
                //if it's a directory go to the directory and do ftp_sync on this directory
                if (@ftp_chdir($this->conn_id, $file)) {
                    ftp_chdir($this->conn_id, "..");

                    $this->ftp_sync($file,  $nb_files_downloaded);
                } else {

                    //reconnect to ftp_server for each 10 downloaded files
                    if ($nb_files_downloaded % self::NB_DOWNLOADED_FILE_LIMIT === 0) {

                        ftp_close($this->conn_id);
                        $this->ftpConnect($dir);
                    }
                    if ($this->reg_filter !== "") {
                        if (preg_match($this->reg_filter, $file)) {

                            if (ftp_get($this->conn_id, $file, $file, FTP_BINARY)) {
                                $nb_files_downloaded++;

                            } else {
                                $this->writeln("<error>Il y a un problème lors du téléchargement du fichier $file dans $file</error>");
                            }
                        }
                    } else {

                        if (ftp_get($this->conn_id, $file, $file, FTP_BINARY)) {
                            $nb_files_downloaded++;

                        } else {
                            $this->writeln("<error>Il y a un problème lors du téléchargement du fichier $file dans $file</error>");
                        }
                    }


                }
            }
            ftp_chdir($this->conn_id, "..");
            chdir("..");
        }


    }

    /**
     * Conenct to ftp serveur and move to directory
     *
     * @param string $dir
     */
    public function ftpConnect($dir = "."): void
    {
        if (!$this->conn_id = ftp_connect($this->ftp_server))
            $this->writeln("<error>Couldn't connect to $this->ftp_server</error>");

        $login_result = ftp_login($this->conn_id, $this->ftp_user, $this->ftp_password);
        if ((!$this->conn_id) || (!$login_result))
            $this->writeln("<error>FTP Connection Failed</error>");

        ftp_pasv($this->conn_id, true);
        ftp_chdir($this->conn_id, $dir);
    }

    /**
     * @return mixed
     */
    public function getRegFilter()
    {
        return $this->reg_filter;
    }

    /**
     * @param mixed $reg_filter
     */
    public function setRegFilter($reg_filter)
    {
        $this->reg_filter = $reg_filter;
    }

    /**
     * @return mixed
     */
    public function getFtpPassword()
    {
        return $this->ftp_password;
    }

    /**
     * @param mixed $ftp_password
     */
    public function setFtpPassword($ftp_password)
    {
        $this->ftp_password = $ftp_password;
    }

    /**
     * @return mixed
     */
    public function getFtpUser()
    {
        return $this->ftp_user;
    }

    /**
     * @param mixed $ftp_user
     */
    public function setFtpUser($ftp_user)
    {
        $this->ftp_user = $ftp_user;
    }

    /**
     * @return mixed
     */
    public function getFtpServer()
    {
        return $this->ftp_server;
    }

    /**
     * @param mixed $ftp_server
     */
    public function setFtpServer($ftp_server)
    {
        $this->ftp_server = $ftp_server;
    }

    private function writeln(string $string)
    {
        echo $string ."\n";
    }
}