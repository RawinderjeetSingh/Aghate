<?php
/**
\brief Gestion du SGBD 
 
Classe SQL : Cette classe permet la connexion entre l'application et le SGBD
Elle sera utilisée par les différentes méthodes de la couche métier.
*/
 
class Oracle{
 
	protected $user;
	protected $pass;
	protected $server;
	protected $connexionEnCours;
	protected $nbligneaf = 0; // ** Retourne le nombre de ligne affecté par la dernière requête */
	protected $statut = 'Déconnecté';
	

	
	function Oracle($serveur)
	{
		$this->user = " ";
		$this->pass = " ";
		$this->server = $serveur;
		
		$this->connexionEnCours = oci_connect($this->user,$this->pass,$this->server); //PHP 5
		//$this->connexionEnCours = ocilogon($this->user,$this->pass,$this->server);		//php4
		if($this->connexionEnCours) 
			$this->statut = 'Connecté au'.$this->server;
		else
			$this->statut = 'Erreur connexion :'.$this->server.' !!!';
	}
	
	function close()
	{
		//On se déconnecte du serveur
		ocilogoff($this->connexionEnCours);
		$this->statut = 'Déconnecté';
	}
	
	
 
	/**
	\param string req requête sql à executer
	\param bool indique si on doit retourner un objet via oci_fetch_object ou non, retour vaut true si oui false si non.
	*/
	function select($req,$objet = null)
	{
	 
        //On parse la requête à effectuer sans oublier de lui passer la chaine de connexion en paramêtre
		$statement = ociparse($this->connexionEnCours,$req);
		
		ociexecute($statement);
	
		 $tab = array();
		if($objet)
		{
			while($ligne = oci_fetch_object($statement,OCI_BOTH))
			{
				$tab[] = $ligne;
			}
		}
		else
		{
			while($ligne = oci_fetch_array($statement,OCI_BOTH))
			{
				$tab[] = $ligne;
			}
		}
		
		return $tab;
		
	}
	

}
?>
