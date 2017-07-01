/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package pokergame;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.URL;
import java.net.URLConnection;
import javax.swing.JOptionPane;

/**
 *
 * @author SHER
 */
public class HttpWrapper {
    public String gameurl="http://localhost/Poker/Index.php";
       public String roundurl="http://localhost/Poker/round.php";
    public String connecturl=this.gameurl.concat("?action=CONNECT");
    public String list_players=this.gameurl.concat("?action=PLAYER_LIST");
    public String intial_cards=this.roundurl.concat("?action=GET_INITIAL_CARDS");
     public String my_hand=this.roundurl.concat("?action=CURRENT_HAND");
    public String checknext=this.roundurl.concat("?action=CHECK_WHOS_NEXT");
    
     public String new_bet=this.roundurl.concat("?action=NEW_BET");
       public String new_middle_card=this.roundurl.concat("?action=GET_NEW_MIDDLE_CARD");
     
   public String getRemoteContents(String url) {
    String inputLine, output = "";
    try{
    URL urlObject = new URL(url);
    URLConnection conn = urlObject.openConnection();
        try (BufferedReader in = new BufferedReader(new InputStreamReader(conn.getInputStream()))) {
            while ((inputLine = in.readLine()) != null) {
                output += inputLine;
            }   }
        
    
       }
       catch(Exception e){
            JOptionPane.showMessageDialog(null,"Error Occured While Connecting to Server");
       }
    return output;
} 
}
