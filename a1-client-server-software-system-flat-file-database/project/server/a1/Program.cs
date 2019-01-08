/*
*  FILE          : Program.cs
*  PROJECT       : PROG - Relational Databases: Assignment #1
*  PROGRAMMER    : Brendan Rushing
*  FIRST VERSION : 2018-09-23
*  DESCRIPTION   : This assignment has 2 parts: server and client
*   This project is the server. The server manages a file to simulate a database. 
*   The server displays the server IP Address and port when it is launched.
*   
*   The database supports multiple users by assigning a thread to each user that is connected.
*   
*   The server supports communication over a network with socket ip address communication.
*   The server reads the database file when it is started. The server creates a list of a class "database".
*   The database class includes: firstName, lastName and dateofBirth
*           
*   
*   The server accepts messages and determines what to do based on the message.
*       $1 - INSERT
*       $2 - UPDATE
*       $3 - FIND
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



namespace Server
{

    class DataBase
    {
        public string FirstName { get; set; }
        public string LastName { get; set; }
        public string DateOfBirth { get; set; }

    }


    class Program
    {

        const int messageLength = 1024;
        const int databaseSize = 40000;

        static void Main(string[] args)
        {

            try
            {
                int port = 13000;


                //get datafrom database file and store in a list
                List<DataBase> DataBaseTable = new List<DataBase>();

                using (StreamReader sr = new StreamReader("serverDataBase.db"))
                {
                    string line;
                    // Read one line at a time until end of file
                    while ((line = sr.ReadLine()) != null)
                    {
                        //take the line and split it up by , into seperate strings to parse data
                        string[] words = line.Split(',');   
                        //add to database
                        DataBaseTable.Add(new DataBase { FirstName = words[1], LastName = words[2], DateOfBirth = words[3] });
                    }
                }
                //get ip address of server
                string output = Dns.GetHostEntry(Dns.GetHostName()).AddressList[1].ToString();

                //create new socket
                Socket ServerListener = new Socket(AddressFamily.InterNetwork, SocketType.Stream, ProtocolType.Tcp);

                IPEndPoint ep = new IPEndPoint(IPAddress.Parse(output), port);
                ServerListener.Bind(ep);    //bind socket
                ServerListener.Listen(100); //listen for connection
                Console.ForegroundColor = ConsoleColor.Cyan;
                Console.WriteLine("Brendan Rushing's Server Program for Relational Database");
                Console.WriteLine("--------------------------------------------------------");
                Console.ResetColor();
                Console.WriteLine("Server is Listening ... ");
                Console.Write("IP Address: "); //display ip and port
                Console.ForegroundColor = ConsoleColor.Green;
                Console.Write(output +"\n"); //display ip and por
                Console.ResetColor();
                Console.Write("Port #: ");
                Console.ForegroundColor = ConsoleColor.Red;
                Console.Write(port + "\n");
                Console.ResetColor();
                Socket ClientSocket = default(Socket);
                int counter = 0;
                Program p = new Program(); 
                while (true)
                {
                    counter++;  //increment counter for new connection
                    ClientSocket = ServerListener.Accept(); //accept connection
                    Console.WriteLine(counter + " Clients Connected");

                    //CREATE THREAD FOR USER
                    Thread UserThread = new Thread(new ThreadStart(() => p.User(ClientSocket, DataBaseTable)));
                    //START THREAD
                    UserThread.Start();

                }

            }//eo try
            catch (Exception e)
            {
                // Let the user know what went wrong.
                Console.WriteLine("Exception Error: ");
                Console.WriteLine(e.Message);
            }

        }//eo main





        /*
        FUNCTION : User
        DESCRIPTION : This function handles incoming/outgoing connections for 1 user
     
        PARAMETERS : Socket client, List<DataBase> DataBaseTable
        RETURNS : none
        */
        public void User(Socket client, List<DataBase> DataBaseTable)
        {

            try
            {
                while (true)
                {
                    byte[] msg = new byte[messageLength];
                    int size = client.Receive(msg);
                    string outputBuffer;

                    //get message
                    string inputBuffer = System.Text.Encoding.ASCII.GetString(msg, 0, msg.Length);
                    string result = Regex.Replace(inputBuffer, "\0", String.Empty); //remove empty characters
                    string[] words = result.Split(','); //split by , into seperate strings
                    

                    //INSERT FUNCTION
                    if (words[0].Equals("$1") == true)
                    {
                        if (DataBaseTable.Count < 40000)    //check if server is full
                        {

                            try
                            {   //add new entry into database list
                                DataBaseTable.Add(new DataBase { FirstName = words[1], LastName = words[2], DateOfBirth = words[3] });

                                outputBuffer = Convert.ToString(DataBaseTable.Count);
                                //create string for database file
                                outputBuffer = outputBuffer + ',' + DataBaseTable[DataBaseTable.Count - 1].FirstName + ',' +
                                    DataBaseTable[DataBaseTable.Count - 1].LastName + ',' + DataBaseTable[DataBaseTable.Count - 1].DateOfBirth;
                                bool isDone = false;

                                while (isDone == false)
                                {
                                    try
                                    {   //attempt to write string to file until it is available
                                        using (StreamWriter dataBaseFile = File.AppendText("serverDataBase.db"))
                                        {

                                            dataBaseFile.WriteLine(outputBuffer);
                                            isDone = true;
                                        }
                                    }
                                    catch (Exception e)
                                    {
                                        isDone = false;
                                    }
                                }
                                //return success code to client
                                outputBuffer = "S";
                                //send message
                                client.Send(System.Text.Encoding.ASCII.GetBytes(outputBuffer), 0, outputBuffer.Length, SocketFlags.None);
                            }
                            catch (Exception e)
                            {
                                //return error message to client
                                outputBuffer = "E";
                                client.Send(System.Text.Encoding.ASCII.GetBytes(outputBuffer), 0, outputBuffer.Length, SocketFlags.None);
                                throw (e);
                            }

                        }

                        else
                        {   //return server is full message to client
                            outputBuffer = "E-F";
                            client.Send(System.Text.Encoding.ASCII.GetBytes(outputBuffer), 0, outputBuffer.Length, SocketFlags.None);
                        }

                    }//eo $1

                    //UPDATE FUNCTION
                    else if (words[0].Equals("$2") == true)
                    {

                        if (DataBaseTable.Count < 40000)
                        {

                            try
                            {
                                int currentIndex = Convert.ToInt32(words[1]);
                                currentIndex = currentIndex - 1;    //index starts at 0
                                //check if items searched for are in database
                                if (((DataBaseTable[currentIndex].FirstName.Equals(words[2]))
                                && (DataBaseTable[currentIndex].LastName.Equals(words[3]))
                                    && (DataBaseTable[currentIndex].DateOfBirth.Equals(words[4]))))
                                {
                                    //return S for success
                                    outputBuffer = "S";
                                    client.Send(System.Text.Encoding.ASCII.GetBytes(outputBuffer), 0, outputBuffer.Length, SocketFlags.None);
                                }

                                

                            }
                            catch (Exception e)
                            {
                                //return error for not found
                                outputBuffer = "E";
                                client.Send(System.Text.Encoding.ASCII.GetBytes(outputBuffer), 0, outputBuffer.Length, SocketFlags.None);
                                break;  //break out of loop since invalid entry
                            }

                            msg = new byte[messageLength];
                            size = client.Receive(msg);
                            //receive full string with new data for entry
                            inputBuffer = System.Text.Encoding.ASCII.GetString(msg, 0, msg.Length);
                            result = Regex.Replace(inputBuffer, "\0", String.Empty);
                            words = result.Split(',');


                            try
                            {
                                int currentIndex = Convert.ToInt32(words[1]);
                                currentIndex = currentIndex - 1;
                                //update database entry
                                DataBaseTable[currentIndex].FirstName = words[2];
                                DataBaseTable[currentIndex].LastName = words[3];
                                DataBaseTable[currentIndex].DateOfBirth = words[4];
                                
                                File.WriteAllText(@"serverDataBase.db", "");    //clear file




                                using (StreamWriter dataBaseFile = File.AppendText("serverDataBase.db"))
                                {
                                //remake database file
                                for (int i = 0; i < DataBaseTable.Count; i++)
                                    {

                                    bool isDone = false;

                                    //write until done
                                        while (isDone == false)
                                    {
                                    try
                                    {
                                        outputBuffer = Convert.ToString(i + 1);
                                        outputBuffer = outputBuffer + ',' + DataBaseTable[i].FirstName + ',' +
                                        DataBaseTable[i].LastName + ',' + DataBaseTable[i].DateOfBirth;
                                        dataBaseFile.WriteLine(outputBuffer);
                                        isDone = true;
                                    }
                                    catch (Exception e)
                                    {
                                        isDone = false;
                                    }



                                }
                                    }
                                }



                              


                                //write success error code
                                outputBuffer = "S";
                                client.Send(System.Text.Encoding.ASCII.GetBytes(outputBuffer), 0, outputBuffer.Length, SocketFlags.None);
                                
                        
                            }
                            catch (Exception e)
                            {   //send error if exception
                                outputBuffer = "E";
                                client.Send(System.Text.Encoding.ASCII.GetBytes(outputBuffer), 0, outputBuffer.Length, SocketFlags.None);
                            }




                        }
                        else
                        {   //send server full if full
                            outputBuffer = "E-F";
                            client.Send(System.Text.Encoding.ASCII.GetBytes(outputBuffer), 0, outputBuffer.Length, SocketFlags.None);
                        }
                    }//eo $2
                    //FIND FUNCTION
                    else if (words[0].Equals("$3") == true)
                    {
                    
                        try
                        {   //get item from list with memberID
                            int memberID = Convert.ToInt32(words[1]);

                            //send data back to client
                            outputBuffer = memberID.ToString();
                            outputBuffer = outputBuffer + ',' + DataBaseTable[memberID].FirstName + ',' + DataBaseTable[memberID].LastName
                                + ',' + DataBaseTable[memberID].DateOfBirth;

                            client.Send(System.Text.Encoding.ASCII.GetBytes(outputBuffer), 0, outputBuffer.Length, SocketFlags.None);

                        }
                        catch (Exception e)
                        {
                            // eception will be thrown if memberId is not in list
                            //send error to client
                            outputBuffer = "E";
                            client.Send(System.Text.Encoding.ASCII.GetBytes(outputBuffer), 0, outputBuffer.Length, SocketFlags.None);
                            throw (e);
                        }

                    }//eo else if $3

                }//eo while true
            }//eo try
            catch (Exception e)
            {
                // Let the user know what went wrong.
                Console.WriteLine("Exception Error: ");
                Console.WriteLine(e.Message);
            }

        
        }//eo user
    }//eo class Program
}//eo namespace Server
