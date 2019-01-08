/*
*  FILE          : Program.cs
*  PROJECT       : PROG - Relational Databases: Assignment #1
*  PROGRAMMER    : Brendan Rushing
*  FIRST VERSION : 2018-09-23
*  DESCRIPTION   : This assignment has 2 parts: server and client
*   This project is the client. The client accesses a remote server program that uses a file
*   to simulate a database. 
*   The user enters the IP Address and Port # of the server to connect.
*   
*   The database includes the following variables:
*   - FirstName
*   - LastName
*   - MemberID
*   - DateOfBirth
*   
*   The client can perform the following functions:
*   - INSERT:   user enters values for the variables and the entry is sent to the server database
*   - FIND:     user enters a memberID and the information for that entry is returned to the client
*   - UPDATE:   user enters a memberID, firstName, lastName and dateOfBirth. The user can then update the values of the variables.
*   
*   - Bulk Data Entry:  user enters how many entries to insert and the client uses a file with firstNames and a file with LastNames
*                       a random date of birth is generated and the entries are sent to the server one at a time.
*                       
*	
*/


//INCLUDES
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.IO;
using System.Net;
using System.Net.Sockets;
using System.Threading;
using System.Text.RegularExpressions;
//eo INCLUDES





namespace a1client
{

    class Program
    {
        //CONSTANTS
        const int NAME_LENGTH = 25;
        const int messageType_Insert = 0;
        const int messageType_Update = 1;
        const int messageType_Find = 0;
        const int messageReturnLength = 100;
        const int messageSendLength = 1024;
        const int EXIT_SUCCESS = 0;
        //eo CONSTANTS


        static int Main(string[] args)
        {
            try
            {
                bool serverConnected = false;
                Socket ClientSocket = new Socket(AddressFamily.InterNetwork, SocketType.Stream, ProtocolType.Tcp);  //make socket class
                while (serverConnected == false)
                {
                    Console.ForegroundColor = ConsoleColor.Cyan;
                    Console.WriteLine("Brendan Rushing's Client Program for Relational Database");
                    Console.WriteLine("--------------------------------------------------------");
                    Console.ResetColor();
                    Console.WriteLine("Enter Server ID Address: "); //get server ip and port
                    string IpAddress = Console.ReadLine();
                    Console.WriteLine("Enter Server Port #: ");
                    int port = Convert.ToInt32(Console.ReadLine());

                    try
                    {


                        IPEndPoint ep = new IPEndPoint(IPAddress.Parse(IpAddress), port); //make ip endpoint for comm.
                        ClientSocket.Connect(ep);   //connect socket
                        Console.WriteLine("Client is connected!");  //successfully connected
                        serverConnected = true;
                        Console.Clear();
                    }
                    catch (Exception e)
                    {
                        serverConnected = false;        //could not connect, try again
                        Console.WriteLine(e);
                    }


                }

                while (true)
                {
                    //Console.Clear();
                    Console.ForegroundColor = ConsoleColor.Cyan;
                    Console.WriteLine("Brendan Rushing's Client Program for Relational Database");
                    Console.WriteLine("--------------------------------------------------------");
                    Console.ResetColor();
                    Console.WriteLine("Menu:");
                    Console.WriteLine("1. Insert");
                    Console.WriteLine("2. Update");
                    Console.WriteLine("3. Find");
                    Console.WriteLine("4. Close");
                    Console.WriteLine("5. Bulk Client");
                    int userInput = Convert.ToInt32(Console.ReadLine());    //get user entry
                    string firstName = null;
                    string lastName = null;
                    string date = null;
                    string outBuffer = null;



                    switch (userInput)
                    {
                        case 1:
                            Console.Clear();
                            Console.ForegroundColor = ConsoleColor.Cyan;
                            Console.WriteLine("Brendan Rushing's Client Program for Relational Database");
                            Console.WriteLine("--------------------------------------------------------");
                            Console.ResetColor();
                            Console.WriteLine("Insert new item in database");
                            bool dateCorrect = false;
                            bool nameValid = false;

                            while (nameValid == false)
                            {
                                Console.WriteLine("Enter First Name (25 char max): ");
                                firstName = Console.ReadLine();
                                if (firstName.Length <= NAME_LENGTH)
                                {
                                    nameValid = true;
                                }

                            }
                            nameValid = false;
                            while (nameValid == false)
                            {

                                Console.WriteLine("Enter Last Name (25 char max) : ");
                                lastName = Console.ReadLine();

                                if (lastName.Length <= NAME_LENGTH)
                                {
                                    nameValid = true;
                                }

                            }
                            while (dateCorrect == false)
                            {
                                Console.WriteLine("Enter Date of Birth (MM-DD-YYYY): ");
                                date = Console.ReadLine();

                                var regex = new Regex(@"^\d{1,2}-\d{1,2}-\d{4}$");      //regular expression for date: MM-DD-YYYY

                                if (!regex.IsMatch(date))
                                {
                                    dateCorrect = false;    //if regex does not match
                                    Console.WriteLine("Invalid date entry.");
                                }
                                else
                                {
                                    dateCorrect = true; //date is correct entry
                                }

                            }

                            //SEND DATA TO SERVER
                            outBuffer = "$1," + firstName + ',' + lastName + ',' + date;
                            ClientSocket.Send(System.Text.Encoding.ASCII.GetBytes(outBuffer), 0, outBuffer.Length, SocketFlags.None);

                            //GET RETURN CODE FROM SERVER
                            byte[] msg = new byte[messageReturnLength];
                            int size = ClientSocket.Receive(msg);
                            string inputBuffer = System.Text.Encoding.ASCII.GetString(msg, 0, msg.Length);

                            //ERROR - SERVER FULL
                            if (inputBuffer.Contains("E-F"))
                            {
                                Console.WriteLine("Server Error: database is full (40,000 records)");
                            }
                            //ERROR - SERVER ERROR
                            else if (inputBuffer.Contains("E"))
                            {
                                Console.WriteLine("Server Error: writing to database");
                            }
                            //SUCCESS
                            else
                            {
                                Console.WriteLine("Success writing to database");
                            }

                            break;

                        case 2:
                            Console.Clear();
                            Console.ForegroundColor = ConsoleColor.Cyan;
                            Console.WriteLine("Brendan Rushing's Client Program for Relational Database");
                            Console.WriteLine("--------------------------------------------------------");
                            Console.ResetColor();
                            Console.WriteLine("Update item in database");
                            string memberID;
                            Console.WriteLine("Enter MemberID: ");
                            memberID = Console.ReadLine();
                            dateCorrect = false;
                            nameValid = false;

                            //GET FIRST NAME
                            while (nameValid == false)
                            {
                                Console.WriteLine("Enter First Name (25 char max): ");
                                firstName = Console.ReadLine();
                                if (firstName.Length <= NAME_LENGTH)
                                {
                                    nameValid = true;
                                }

                            }
                            nameValid = false;
                            //GET LAST NAME
                            while (nameValid == false)
                            {

                                Console.WriteLine("Enter Last Name (25 char max) : ");
                                lastName = Console.ReadLine();

                                if (lastName.Length <= NAME_LENGTH)
                                {
                                    nameValid = true;
                                }

                            }
                            //GET DATE OF BIRTH
                            while (dateCorrect == false)
                            {
                                Console.WriteLine("Enter Date of Birth (MM-DD-YYYY): ");
                                date = Console.ReadLine();

                                var regex = new Regex(@"^\d{1,2}-\d{1,2}-\d{4}$");

                                if (!regex.IsMatch(date))
                                {
                                    dateCorrect = false;
                                    Console.WriteLine("Invalid date entry.");
                                }
                                else
                                {
                                    dateCorrect = true;
                                }

                            }

                            //SEND DATA TO SERVER
                            outBuffer = "$2," + memberID + ',' + firstName + ',' + lastName + ',' + date;
                            ClientSocket.Send(System.Text.Encoding.ASCII.GetBytes(outBuffer), 0, outBuffer.Length, SocketFlags.None);

                            //CHECK RETURN CODE
                            msg = new byte[messageReturnLength];
                            size = ClientSocket.Receive(msg);
                            inputBuffer = System.Text.Encoding.ASCII.GetString(msg, 0, msg.Length);

                            //ERROR - SERVER FULL
                            if (inputBuffer.Contains("E-F"))
                            {
                                Console.WriteLine("Server Error: database is full (40,000 records)");
                            }
                            //ERROR SERVER ERROR
                            else if (inputBuffer.Contains("E"))
                            {
                                Console.WriteLine("Server Error: writing to database");
                            }
                            //SUCCESS
                            else
                            {
                                Console.WriteLine("Success: Item found. Enter updated Information.");
                            }

                            dateCorrect = false;
                            nameValid = false;
                            //GET NEW FIRST NAME
                            while (nameValid == false)
                            {
                                Console.WriteLine("Enter First Name (25 char max): ");
                                firstName = Console.ReadLine();
                                if (firstName.Length <= NAME_LENGTH)
                                {
                                    nameValid = true;
                                }

                            }
                            nameValid = false;
                            //GET NEW LAST NAME
                            while (nameValid == false)
                            {

                                Console.WriteLine("Enter Last Name (25 char max) : ");
                                lastName = Console.ReadLine();

                                if (lastName.Length <= NAME_LENGTH)
                                {
                                    nameValid = true;
                                }

                            }
                            //GET NEW DATE OF BIRTH
                            while (dateCorrect == false)
                            {
                                Console.WriteLine("Enter Date of Birth (MM-DD-YYYY): ");
                                date = Console.ReadLine();

                                var regex = new Regex(@"^\d{1,2}-\d{1,2}-\d{4}$");

                                if (!regex.IsMatch(date))
                                {
                                    dateCorrect = false;
                                    Console.WriteLine("Invalid date entry.");
                                }
                                else
                                {
                                    dateCorrect = true;
                                }

                            }
                            //SEND DATA
                            outBuffer = "$2," + memberID + ',' + firstName + ',' + lastName + ',' + date;
                            ClientSocket.Send(System.Text.Encoding.ASCII.GetBytes(outBuffer), 0, outBuffer.Length, SocketFlags.None);

                            //CHECK RETURN CODE
                            msg = new byte[messageReturnLength];
                            size = ClientSocket.Receive(msg);
                            inputBuffer = System.Text.Encoding.ASCII.GetString(msg, 0, msg.Length);
                            //ERROR FULL
                            if (inputBuffer.Contains("E-F"))
                            {
                                Console.WriteLine("Server Error: database is full (40,000 records)");
                            }
                            //ERROR SERVER ERROR
                            else if (inputBuffer.Contains("E"))
                            {
                                Console.WriteLine("Server Error: writing to database");
                            }
                            else
                            //SUCCESS
                            {
                                Console.WriteLine("Success: Item has been updated.");
                            }



                            break;

                        case 3:
                            Console.Clear();
                            Console.ForegroundColor = ConsoleColor.Cyan;
                            Console.WriteLine("Brendan Rushing's Client Program for Relational Database");
                            Console.WriteLine("--------------------------------------------------------");
                            Console.ResetColor();
                            Console.WriteLine("Find item in database by entering memberID");    //GET MEMBERID TO SEARCH
                            Console.WriteLine("Enter MemberID: ");
                            memberID = Console.ReadLine();
                            //SEND DATA
                            outBuffer = "$3," + memberID;
                            ClientSocket.Send(System.Text.Encoding.ASCII.GetBytes(outBuffer), 0, outBuffer.Length, SocketFlags.None);
                            //RECEIVE ERROR CODE OR DATABASE ENTRY
                            msg = new byte[messageSendLength];
                            size = ClientSocket.Receive(msg);
                            inputBuffer = System.Text.Encoding.ASCII.GetString(msg, 0, msg.Length);
                            //SERVER ERROR
                            if (inputBuffer.Equals("E"))
                            {
                                Console.WriteLine("Server Error: item not found");
                            }
                            else
                            {   //OUTPUT RETURNED ENTRY FROM SERVER
                                string result = Regex.Replace(inputBuffer, "\0", String.Empty);
                                string[] words = result.Split(',');
                                Console.WriteLine("MemberID: " + words[0]);
                                Console.WriteLine("FirstName: " + words[1]);
                                Console.WriteLine("LastName: " + words[2]);
                                Console.WriteLine("Date: " + words[3]);

                            }

                            break;
                        case 4:
                            //CLOSE CLIENT PROGRAM
                            ClientSocket.Shutdown(SocketShutdown.Both);
                            ClientSocket.Close();
                            return 0;

                        case 5:
                            Console.Clear();
                            Console.ForegroundColor = ConsoleColor.Cyan;
                            Console.WriteLine("Brendan Rushing's Client Program for Relational Database");
                            Console.WriteLine("--------------------------------------------------------");
                            Console.ResetColor();
                            Console.WriteLine("Bulk insert items into database");
                            Console.WriteLine("Enter Number of items: ");
                            int numberOfItems = Convert.ToInt32(Console.ReadLine());

                            List<string> firstNames = new List<string>();

                            using (StreamReader sr = new StreamReader("firstnames.txt"))
                            {
                                string line;
                                int firstNameCounter = 0;
                                // Read and display lines from the file until the end of 
                                // the file is reached.
                                while ((line = sr.ReadLine()) != null)

                                {
                                    //Console.WriteLine(line);
                                    firstNames.Insert(firstNameCounter, line);
                                    firstNameCounter++;

                                }
                            }

                            List<string> lastNames = new List<string>();

                            using (StreamReader sr = new StreamReader("lastnames.txt"))
                            {
                                string line;
                                int lastNameCounter = 0;
                                // Read and display lines from the file until the end of 
                                // the file is reached.
                                while ((line = sr.ReadLine()) != null)

                                {
                                    //Console.WriteLine(line);
                                    lastNames.Insert(lastNameCounter, line);
                                    lastNameCounter++;

                                }
                            }


                            for (int i = 0; i < numberOfItems; i++)
                            {

                                Random r = new Random();
          
                                int rInt = r.Next(1, 12);                   //random number 1 to 12  for months
                                date = rInt.ToString().PadLeft(2, '0');     //2 digits wide

                                rInt = r.Next(1, 31);                        //random number 1 to 31  for days
                                date = date + "-" + rInt.ToString().PadLeft(2, '0');    //2 digits wide

                                rInt = r.Next(1900, 2018);              //random year 1900 to 2018
                                date = date + "-" + rInt.ToString();   

                                r = new Random();

                                firstName = firstNames[r.Next(1,firstNames.Count)]; //get random first name from file
                                Thread.Sleep(1);        //sleep 1 cycle to allow random to get unique values

                                r = new Random();

                                lastName = lastNames[r.Next(1, lastNames.Count)];   //get random last name from file

                                outBuffer = "$1," + firstName + ',' + lastName + ',' + date;    //combine full message for INSERT

                                //SEND DATA TO SERVER
                                ClientSocket.Send(System.Text.Encoding.ASCII.GetBytes(outBuffer), 0, outBuffer.Length, SocketFlags.None);
                               

                                msg = new byte[100];
                                //WAIT FOR RETURN CODE FROM SERVER
                                size = ClientSocket.Receive(msg);
                                inputBuffer = System.Text.Encoding.ASCII.GetString(msg, 0, msg.Length);

                                //SERVER FULL ERROR
                                if (inputBuffer.Contains("E-F"))
                                {
                                    Console.WriteLine("#" + (i+1) + "  Server Error: database is full (40,000 records)");
                                    break;
                                    
                                }
                                //SERVER ERROR CODE
                                else if (inputBuffer.Contains("E"))
                                {
                                    Console.WriteLine("#" + i + "  Server Error: writing to database");
                                    break;
                                    
                                }
                                //SUCCESS FROM SERVER
                                else
                                {
                                    Console.WriteLine("#" + i + " - Success writing to database");
                                }
                            }//eo for
                        
                            break;

                    }//eo switch
                }//eo whle true

            }//eo try

            catch (Exception e)
            {
                // Let the user know what went wrong.
                Console.WriteLine("Exception Error: ");
                Console.WriteLine(e.Message);
            }


            return EXIT_SUCCESS;

        }//eo main
    }//eo class program
}//eo namespace
