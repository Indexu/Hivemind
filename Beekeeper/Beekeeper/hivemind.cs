using MySql.Data.MySqlClient;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Security.Cryptography;
using System.Windows;


namespace Beekeeper
{
    class hivemind
    {

        private string server;
        private string database;
        private string uid;
        private string password;
        string connection = null;
        string query = null;

        MySqlConnection sqlConnect;
        MySqlCommand sqlCommand;
        MySqlDataReader sqlRead = null;

        //DataGrid Account setup
        public class Bees
        {
            public int accountID { get; set; }
            public string alias { get; set; }
            public string email { get; set; }
            public int confirmed { get; set; }
            public string status { get; set; }
        }

        //DataGrid User setup
        public class BeesUsers
        {
            public string alias { get; set; }
            public string bio { get; set; }
            public string joinDate { get; set; }
            public string friends { get; set; }
        }


        //Tengist við gagnagrunn
        public void connectDB()
        {
            server = "82.148.66.15";
            database = "gru_h1_hivemind";
            //uid = "root";
            //password = "''";
            uid = "NOPE";
            password = "NOPE";

            connection = "server=" + server + ";userid=" + uid + ";password=" + password + ";database=" + database;

            sqlConnect = new MySqlConnection(connection);
        }
        //opna fyrir tengingu
        private bool OpenConnection()
        {
            try
            {
                sqlConnect.Open();
                return true;
            }
            catch (MySqlException ex)
            {

                throw ex;
            }
        }
        //loka fyrir tengingu
        private bool CloseConnection()
        {
            try
            {
                sqlConnect.Close();
                return true;
            }
            catch (MySqlException ex)
            {

                throw ex;
            }
        }

        //sæki upplýsingar
        public List<Bees> readSqlAccounts()
        {
            List<Bees> bees = new List<Bees>();
            int id = 0;
            string alias = null;
            string mail = null;
            int confirmed = 0;
            string status = null;

            if (OpenConnection() == true)
            {
                query = "SELECT accounts.accountID, profile.alias, accounts.email, accounts.confirmed, accounts.status FROM accounts JOIN profile ON accounts.accountID=profile.accountID ";
                sqlCommand = new MySqlCommand(query, sqlConnect);

                sqlRead = sqlCommand.ExecuteReader();

                while (sqlRead.Read())
                {
                    for (int i = 0; i < sqlRead.FieldCount; i++)
                    {
                        switch (i)
                        {
                            case 0:
                                id = Convert.ToInt32(sqlRead.GetValue(i));
                                break;

                            case 1:
                                alias = sqlRead.GetValue(i).ToString();
                                break;

                            case 2:
                                mail = sqlRead.GetValue(i).ToString();
                                break;

                            case 3:
                                confirmed = Convert.ToInt32(sqlRead.GetValue(i));
                                break;

                            case 4:
                                status = sqlRead.GetValue(i).ToString();
                                break;

                            default:
                                break;
                        }
                    }

                    bees.Add(new Bees() {
                        accountID = id,
                        alias = alias,
                        email = mail,
                        confirmed=confirmed,
                        status = status
                        
                    });
                }

                CloseConnection();
                return bees;
            }
            return bees;
        }

        //sæki upplýsingar fyrir User tabið
        public List<BeesUsers> readSqlUser()
        {
            List<BeesUsers> bees = new List<BeesUsers>();
            string alias = null;
            string bio = null;
            string joinDate = null;
            string friends = null;

            if (OpenConnection() == true)
            {
                query = "SELECT profile.alias, profile.bio, profile.joinDate, friends.friends FROM profile JOIN friends ON (profile.accountID=friends.accountID)";
                sqlCommand = new MySqlCommand(query, sqlConnect);

                sqlRead = sqlCommand.ExecuteReader();

                while (sqlRead.Read())
                {
                    for (int i = 0; i < sqlRead.FieldCount; i++)
                    {
                        switch (i)
                        {
                            case 0:
                                alias = sqlRead.GetValue(i).ToString();
                                break;

                            case 1:
                                bio = sqlRead.GetValue(i).ToString();
                                break;

                            case 2:
                                joinDate = sqlRead.GetValue(i).ToString();
                                break;

                            case 3:
                                friends = sqlRead.GetValue(i).ToString();
                                break;

                            default:
                                break;
                        }
                    }

                    bees.Add(new BeesUsers()
                    {
                        alias = alias,
                        bio = bio,
                        joinDate = joinDate,
                        friends = friends
                    });
                }

                CloseConnection();
                return bees;
            }
            return bees;
        }

        //Aðferð til að inserta
        public void Insert(string email, string alias, string status, int confirmed)
        {
            if (OpenConnection() == true)
            {
                query = "INSERT INTO accounts (email,password,salt,status,confirmed) VALUES('" + email + "','password','salt','" + status + "'," + confirmed +")";
                sqlCommand = new MySqlCommand(query, sqlConnect);
                sqlCommand.ExecuteNonQuery();

                int accountID = 0;

                query = "SELECT MAX(accountID) FROM accounts";
                sqlCommand = new MySqlCommand(query, sqlConnect);

                sqlRead = sqlCommand.ExecuteReader();

                while (sqlRead.Read())
                {
                    for (int i = 0; i < sqlRead.FieldCount; i++)
                    {
                        accountID = Convert.ToInt32(sqlRead.GetValue(i));
                    }
                }

                sqlRead.Close();

                query = "INSERT INTO profile (alias, accountID) VALUES('" + alias + "'," + accountID + ")";
                sqlCommand = new MySqlCommand(query, sqlConnect);
                sqlCommand.ExecuteNonQuery();
                CloseConnection();
            }
        }

        //aðferð til þess upfæra
        public void Update(int accountID, string email, string status, int confirmed)
        {
            if (OpenConnection() == true)
            {
                query = "UPDATE accounts SET email ='" + email + "',status='" + status + "',confirmed='" + confirmed + "' WHERE accountID=" + accountID;
                sqlCommand = new MySqlCommand(query, sqlConnect);
                sqlCommand.ExecuteNonQuery();
                CloseConnection();
            }
        }
        //aðferð til þess eyða
        public void Delete(int accountID)
        {
            if (OpenConnection() == true)
            {
                query = "DELETE FROM friends WHERE accountID = '" + accountID + "'";
                sqlCommand = new MySqlCommand(query, sqlConnect);
                sqlCommand.ExecuteNonQuery();
                query = "DELETE FROM profile WHERE accountID = '" + accountID + "'";
                sqlCommand = new MySqlCommand(query, sqlConnect);
                sqlCommand.ExecuteNonQuery();
                query = "DELETE FROM accounts WHERE accountID = '" + accountID + "'";
                sqlCommand = new MySqlCommand(query, sqlConnect);
                sqlCommand.ExecuteNonQuery();
                query = "UPDATE threads SET accountID = -1 WHERE accountID= '" + accountID + "'";
                sqlCommand = new MySqlCommand(query, sqlConnect);
                sqlCommand.ExecuteNonQuery();
                CloseConnection();
            }
        }

        internal void Update(string mail, string pass, string alias, string status)
        {
            throw new NotImplementedException();
        }
    }
}
