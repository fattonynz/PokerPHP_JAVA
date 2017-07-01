/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package pokergame;

import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.swing.ImageIcon;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import org.json.JSONArray;
import org.json.JSONObject;

/**
 *
 * @author SHER
 */
public class PokerMain {
 public int room_id;
 public int my_playerid;
 public boolean game_started=false;
 public String game_id="";
 public int highest_bid=0;
 
 public int player1,player2,player3,player4,player5,player6;
 ScheduledExecutorService place_bet_exec;
  HttpWrapper http=new HttpWrapper();
 public void get_players(PokerMatch pm){
     ScheduledExecutorService exec = Executors.newSingleThreadScheduledExecutor();
exec.scheduleAtFixedRate(new Runnable() {
  @Override
  public void run() {
      if(game_started==false){
 
        String user_list_url=http.list_players.concat("&room_id=").concat((String)String.valueOf(room_id)).concat("&player_id=").concat(String.valueOf(my_playerid));
       String result;
    try {
        result = http.getRemoteContents(user_list_url); 
     System.out.println(result);
        JSONObject obj = new JSONObject(result);
         game_started=obj.getBoolean("game_started");
        if(game_started==true){
        game_id=obj.getString("game_id");
        pm.game_id_lbl.setText(String.valueOf(game_id));
       // pm.poker_match_id.setText(String.valueOf( game_id));
          pm.main_label_center.setVisible(false);
     //   JOptionPane.showMessageDialog(null,"Game Is Starting"); 
         get_intial_cards(pm);
         get_my_hand(pm);
         check_for_bet(pm);
        }
        else{
            
         pm.main_label_center.setText("Game Has Not Started Yet");
            
        }
JSONArray arr = obj.getJSONArray("players");
int id=0;
String player_name="";
JSONObject jk;
int d=0;
for (int i = 0; i < arr.length(); i++){
   id=arr.getJSONObject(i).getInt("id");
    player_name=arr.getJSONObject(i).getString("name");
    
    if(i==0){pm.player1_label.setText(player_name);player1=id;}
    if(i==1){pm.player2_label.setText(player_name);player2=id;}
    if(i==2){pm.player3_label.setText(player_name);player3=id;}
    if(i==3){pm.player3_label.setText(player_name);player4=id;}
     if(i==4){pm.player5_label.setText(player_name);player5=id;}
     if(i==5){pm.player6_label.setText(player_name);player6=id;}
     
    
    
    
       // System.out.println(player_name);
}
    } catch (Exception ex) {
        Logger.getLogger(PokerMatch.class.getName()).log(Level.SEVERE, null, ex);
    }    
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
  }}
}, 0, 5, TimeUnit.SECONDS);

 }
 public void get_total_chip_count(PokerMatch pm){
     int c1=Integer.parseInt(pm.player1_chips.getText());
      // JOptionPane.showMessageDialog(null,c1);
     int c2=Integer.parseInt(pm.player2_chips.getText());
       int c3=Integer.parseInt(pm.player3_chips.getText());
        int c4=Integer.parseInt(pm.player4_chips.getText());
         int c5=Integer.parseInt(pm.player5_chips.getText());
          int c6=Integer.parseInt(pm.player6_chips.getText());
          try{
          int total_chips=c1+c2+c3+c4+c5+c6;
          pm.total_chip_count.setText(String.valueOf(total_chips));
            JOptionPane.showMessageDialog(null,String.valueOf(total_chips));
          }
          catch(Exception e){
           JOptionPane.showMessageDialog(null,e.getMessage());
          }

     
 }
 public void get_my_hand(PokerMatch pm){
     String myid=String.valueOf(my_playerid);
     pm.player_id.setText(myid);
    String myhand_url=http.my_hand.concat("&game_id=").concat(game_id).concat("&player_id=").concat(myid);
     String result;
     result = http.getRemoteContents(myhand_url); 
     System.out.println(result);
      JSONObject obj = new JSONObject(result);
    JSONArray arr = obj.getJSONObject("cards").getJSONArray("hand");
  for (int i = 0; i < arr.length(); i++){
 
    if(i==0){ assign_image( pm.mycard1,arr.getString(i));}
     if(i==1){ assign_image( pm.mycard2,arr.getString(i));}

  }  
 }
 
 public void get_intial_cards(PokerMatch pm){
     
     String intial_url=http.intial_cards.concat("&game_id=").concat(game_id);
     String result;
     
     result = http.getRemoteContents(intial_url); 
      System.out.println(result);
  //  JOptionPane.showMessageDialog(null,result);
      JSONObject obj = new JSONObject(result);
    JSONArray arr = obj.getJSONArray("cards");
  for (int i = 0; i < arr.length(); i++){
 
    if(i==0){
    assign_image( pm.middle_card1,arr.getString(i));
    }
     if(i==1){  assign_image( pm.middle_card2,arr.getString(i));}
   if(i==2){  assign_image( pm.middle_card3,arr.getString(i));}
   if(i==3){  assign_image( pm.middle_card4,arr.getString(i));}
   if(i==4){  assign_image( pm.middle_card5,arr.getString(i));}
   //  if(i==6){  assign_image( pm.middle_card6,arr.getString(i));}
  }
    
 }
 
 
 public String get_next_chance_info(PokerMatch pm){
     String intial_url=http.checknext.concat("&game_id=").concat(game_id).concat("&player_id=").concat(String.valueOf(my_playerid));
 String result;
     result = http.getRemoteContents(intial_url); 
      System.out.println(result);
    // pm.logbox.append(result);
   //JOptionPane.showMessageDialog(null,result);
     return result;
 }
 public void show_betting_panel(PokerMatch pm){
 
  pm.betting_panel.setVisible(true);
 }
 public void place_bet(PokerMatch pm,String type){
     
  pm.raise_slider.setMinimum(highest_bid);
  pm.raise_slider.setMaximum(highest_bid+50);
  int raised_amount=pm.raise_slider.getValue();
  String bet_url=http.new_bet.concat("&type=").concat(type).concat("&raised_amount=").concat(String.valueOf(raised_amount)).concat("&highest_bid=").concat(String.valueOf(highest_bid)).concat("&game_id=").concat(game_id).concat("&player_id=").concat(String.valueOf(my_playerid));
 String result;
     result = http.getRemoteContents(bet_url); 
 System.out.println(result);
       pm.betting_panel.setVisible(false);
 }
 public JLabel select_correct_player_labels(PokerMatch pm,String type,int player_id){
     JLabel lbl=null;
   if(player1==player_id){
         switch (type) {
             case "BET_STATUS":
                 lbl=pm.player1_bet_status1;
                 break;
             case "CHIPS":
                 lbl= pm.player1_chips;
                 break;
             case "CHIP_BALANCE":
                 lbl= pm.player1_balance;
                 break;
             default:
                 break;
         }
   }  
     if(player2==player_id){
         switch (type) {
             case "BET_STATUS":
                 lbl= pm.player2_bet_status1;
                 break;
             case "CHIPS":
                 lbl= pm.player2_chips;
                 break;
             case "CHIP_BALANCE":
                 lbl= pm.player2_balance; 
                 break;
             default:
                 break;
         }
     } 
      if(player3==player_id){
         switch (type) {
             case "BET_STATUS":
                 lbl=pm.player3_bet_status1;
                 break;
             case "CHIPS":
                 lbl= pm.player3_chips;
                 break;
             case "CHIP_BALANCE":
                 lbl= pm.player3_balance;
                 break;
             default:
                 break;
         }
   }
       if(player4==player_id){
         switch (type) {
             case "BET_STATUS":
                 lbl= pm.player4_bet_status1;
                 break;
             case "CHIPS":
                 lbl= pm.player4_chips;
                 break;
             case "CHIP_BALANCE":
                 lbl= pm.player4_balance;
                 break;
             default:
                 break;
         }
   }
        if(player5==player_id){
         switch (type) {
             case "BET_STATUS":
                 lbl= pm.player5_bet_status;
                 break;
             case "CHIPS":
                 lbl= pm.player5_chips;
                 break;
             case "CHIP_BALANCE":
                 lbl= pm.player5_balance;
                 break;
             default:
                 break;
         }
   }
         if(player6==player_id){
         switch (type) {
             case "BET_STATUS":
                 lbl= pm.player5_bet_status;
                 break;
             case "CHIPS":
                 lbl=pm.player5_chips;
                 break;
             case "CHIP_BALANCE":
                 lbl= pm.player5_balance;
                 break;
             default:
                 break;
         }
   }
         return lbl;
 }
 public void display_betting_updates_table(PokerMatch pm,String type,int player_id,int bet){
     
     switch(type){
         case "fold":
       JLabel lbl= lbl=select_correct_player_labels(pm,"BET_STATUS", player_id);
         lbl.setText("Fold");
         lbl.setVisible(true);         
         break;
         
         case "call":
           JLabel lbl_call= lbl=select_correct_player_labels(pm,"BET_STATUS", player_id);
           lbl_call.setText("CALL");
           lbl_call.setVisible(true);    
           
           JLabel lbl_call_chip= lbl=select_correct_player_labels(pm,"CHIPS", player_id);
           lbl_call_chip.setText(String.valueOf(bet));
           lbl_call_chip.setVisible(true); 
           
             break;
             
         case "raise":
                JLabel lbl_raise= lbl=select_correct_player_labels(pm,"BET_STATUS", player_id);
          lbl_raise.setText("RAISE");
           lbl_raise.setVisible(true);    
           
           JLabel lbl_raise_chip= lbl=select_correct_player_labels(pm,"CHIPS", player_id);
           lbl_raise_chip.setText(String.valueOf(bet));
           lbl_raise_chip.setVisible(true);  
             
          break;
          
         case "check":
           JLabel lbl_check=select_correct_player_labels(pm,"BET_STATUS", player_id);
           lbl_check.setText("CHECK");
           lbl_check.setVisible(true);   
          break;
     }
     
 }
 public void new_betting_updates(PokerMatch pm,JSONArray  updates){
     int player_id;
     int bet;
     String type;
     int raise;
       for (int i = 0; i < updates.length(); i++){
          player_id=updates.getJSONObject(i).getInt("player_id"); 
          type=updates.getJSONObject(i).getString("type");
          bet=updates.getJSONObject(i).getInt("bet");
       // JOptionPane.showMessageDialog(null,updates.toString());
        
        
        if(type.equals("SUB_ROUND_ENDED")){
            // JOptionPane.showMessageDialog(null,"GETTING NEW CARDS");
          
             add_newcardto_middle(pm);
        }
        else{
            display_betting_updates_table(pm,type,player_id,bet);
        }
       }
 }
 
 public void no_updates(PokerMatch pm){
   JOptionPane.showMessageDialog(null,"No Updates");    
 }
 public void bet_result_decider(PokerMatch pm,String messege,JSONObject obj){
        switch(messege){
          case "PLACE_YOUR_BET":
               highest_bid=obj.getInt("HIGHEST_BID");
               
              //place_bet( pm,highest_bid);
               show_betting_panel(pm);
              //place_bet_exec.shutdown();
            
           break;
           
          case "NEW_UPDATES":
          JSONArray  updates=obj.getJSONArray("updates");
          new_betting_updates(pm,updates);    
          break;
          case "NO_UPDATES":
           no_updates(pm);   
          break;
          case "SUB_ROUND_ENDED":
            //  add_newcardto_middle(pm);
            //  get_total_chip_count(pm);
              break;
          
      }
 }
 public void check_for_bet(PokerMatch pm){
    
      place_bet_exec = Executors.newSingleThreadScheduledExecutor();
      
place_bet_exec.scheduleAtFixedRate(new Runnable() {
  @Override
  public void run() {
       JSONObject obj = new JSONObject(get_next_chance_info(pm));
     // String messege=obj.getString("messege");
    //  System.out.println(obj.toString());
      // JOptionPane.showMessageDialog(null,messege);
   // pm.logbox.append(obj.toString());
   // pm.logbox.append("\n\r");
    // pm.logbox.append("\r\n \n");
   
      
      if(obj.has("messege")){
      String messege=obj.getString("messege");
      bet_result_decider( pm,messege,obj);    
      }
        if(obj.has("messege2")){
      String messege2=obj.getString("messege2");
      bet_result_decider( pm,messege2,obj);    
      }
      
      
      
      
      }}, 0, 5, TimeUnit.SECONDS);
  }
 
 
 
 
 public void add_newcardto_middle(PokerMatch pm){
       //get_total_chip_count(pm);
     pm.hide_unwanted_elements();
    String new_card_url=http.new_middle_card.concat("&game_id=").concat(game_id).concat("&player_id=").concat(String.valueOf(my_playerid));
 String result;
 result = http.getRemoteContents(new_card_url); 
  System.out.println(result);
 JSONObject obj = new JSONObject(result);  
  String card=obj.getString("card");
 int card_position=obj.getInt("position");
  if(card_position==4){
      assign_image( pm.middle_card4,card);
   
  }
  else{
    assign_image( pm.middle_card5,card);
  }
 }
 
 
 
 
public void assign_image(JLabel lbl,String card_name){
String path_to_images="/pokergame/poker_images/";
String card_fullname=card_name.replace("|","");
String image_path=path_to_images+card_fullname.concat(".jpg");
 //JOptionPane.showMessageDialog(null,image_path);    
   System.out.println(card_name);
  System.out.println(image_path);
lbl.setIcon(new ImageIcon(getClass().getResource(image_path)));
} 
 
 
 
 
 
 
}
