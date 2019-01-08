
//FILE : WebForm1.aspx.cs
//NAME : Attila Katona, Brendan Rushing
//PROJECT : PROG2110 - Lab03
//FIRST VERSION : 2018-11-07
//DESCRIPTION : This lab will connect a MySql windows web app to a MySq database over a network. The form will have two
//              buttons, one will find a database entry and display the information. The other button is a insert button
//              that will take the users input and insert the info into the database.


using System;
using System.Collections.Generic;
using System.Configuration;
using System.Data;
using System.Data.SqlClient;
using System.Diagnostics;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using MySql.Data.MySqlClient;

namespace SampleApp
{
    public partial class WebForm1 : System.Web.UI.Page
    {
        private string myConnection = ConfigurationManager.ConnectionStrings["northwindConnectionString"].ConnectionString;        

        protected void Page_Load(object sender, EventArgs e)
        {

        }
        //Below is the FIND button
        protected void Button1_Click(object sender, EventArgs e)
        {
            if (TextBox1.Text.Length == 0)//If user enters nothing
            {
                display("");
            }
            else
            {
                display(TextBox1.Text.ToString());
            }

        }
        //Below is the INSERT BUTTON
        protected void Button2_Click(object sender, EventArgs e)
        {
            const string query = @" INSERT IGNORE INTO Sample (SampleData)
                                    VALUES (@SampleData);";

            using (var myConn = new MySqlConnection(myConnection))
            {
                var cateogoryName = TextBox1.Text;

                var myCommand = new MySqlCommand(query, myConn);
                myCommand.Parameters.AddWithValue("@SampleData", cateogoryName);
                myConn.Open();
                myCommand.ExecuteNonQuery();

                display("");
             
            }
        }

        //
        // FUNCTION : display
        //
        // DESCRIPTION : Displays the information on the database
        //
        // PARAMETERS : String input - Holds the input by the user into the text box
        //
        // RETURNS : None
        //
        protected void display(string input)
        {
            string query = @" SELECT SampleID, SampleData 
                                    FROM Sample;";
            if (input != "")
            {
                query = @" SELECT SampleID, SampleData 
                           FROM Sample
                           WHERE SampleData= @input;";
            }

            using (var myConn = new MySqlConnection(myConnection))
            {
                var cateogoryName = TextBox1.Text.Trim();

                var myCommand = new MySqlCommand(query, myConn);
                myCommand.Parameters.AddWithValue("@input", cateogoryName);

                myConn.Open();

                var dataTable = new DataTable();
                var reader = myCommand.ExecuteReader();
                dataTable.Load(reader);

                GridView1.AutoGenerateColumns = true;
                GridView1.DataSource = dataTable;
                GridView1.DataBind();
            }
        }
    }
}