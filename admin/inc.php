<?php 

    header("Content-type:text/html; charset=koi8-r"); 

    session_start();



/*    if (    

    !(  ($_SESSION['login']==='krab')  && ($_SESSION['pass']==='hz')) 

    )

*/



    if (    

/*    !(  (md5($_SESSION['login'])==='a66c047ca9e1a7e3a1ed92a9ef727a28')

    &&	(md5($_SESSION['pass'])==='eedc49da8bf29645261ea1f5273d099d'))  */

!(  (md5($_SESSION['login'])==='a66c047ca9e1a7e3a1ed92a9ef727a28')
    &&  (md5($_SESSION['pass'])==='eedc49da8bf29645261ea1f5273d099d'))

    &&

    !(  (md5($_SESSION['login'])==='7cde9d0bac4ee5015bd7e7cc8fac5fc3')
    &&  (md5($_SESSION['pass'])==='c953e9b48fb85bb097f29f7946dcaef7'))

    &&

    !(  (md5($_SESSION['login'])==='9822d3e1a8ad6e61512ca1ba2a1a985e')
    &&  (md5($_SESSION['pass'])==='61ed5ebb22db04d6cce3cab246dadd21'))

    &&

    !(  (md5($_SESSION['login'])==='281a16e4bc9cc88cd92b9c8f2c92f45c')
    &&  (md5($_SESSION['pass'])==='fddbcc50e3cd9df6271181f121ad5f56'))
            
            
    &&

    !(  (md5($_SESSION['login'])==='f992ac6e5babbc9f74589913d9297003')
    &&  (md5($_SESSION['pass'])==='2440ccdb2359d3ad5578447d21cda912'))
            
    &&

    !(  (md5($_SESSION['login'])==='e01091fba804dc4678c3ab416a555825')
    &&  (md5($_SESSION['pass'])==='4f7e11633b3f8e44b442d79f35dd540d'))

    &&

    !(  (md5($_SESSION['login'])==='b1708edcabe0a1da903bd7c3141ca12f')
    &&  (md5($_SESSION['pass'])==='c953e9b48fb85bb097f29f7946dcaef7'))

    &&

    !(  (md5($_SESSION['login'])==='db6656838e1bdb349349419831c14156')
    &&  (md5($_SESSION['pass'])==='69e8907af4052fc8cc7fac1b48208db5'))

    &&

    !(  (md5($_SESSION['login'])==='1a42eb234d9b7aacde13acc1ab56925d')
    &&  (md5($_SESSION['pass'])==='efd6425a64b019c3a788d17d6b82b70f'))

    ) {


	 header("Location: index.php");

	 die("!!!");	 

    }

?>

