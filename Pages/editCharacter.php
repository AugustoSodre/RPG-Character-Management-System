<?php 
    session_start();
    require_once("../Configs/db.config.php");

    

    //Get method
    if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['edit'])){
        //Getting the specific character
        try{
            $id = $_GET['edit'];
            $stmt = $pdo->query("SELECT 
            c.id, 
            c.name, 
            c.race, 
            c.class, 
            c.age, 
            c.level,
            c.background,
            c.campaign,
            a.strength, 
            a.dexterity, 
            a.constitution, 
            a.intelligence, 
            a.wisdom, 
            a.charisma
            FROM 
                characters c
            INNER JOIN 
                attributes a ON c.id = a.character_id
            WHERE id = $id
            ");
        
            $character = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            } catch (PDOException $e){
            echo "Something went wrong: " . $e;
            }
    }

    
    //Post method
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_character'])){
        
        //Adding character
        try{

        $stmt = $pdo->prepare("INSERT INTO characters (
            name,
            race,
            age,
            class,
            level,
            campaign,
            background
        ) VALUES(?, ?, ? , ?, ?, ?, ?);");

        $stmt->execute([
            $_POST['name'],
            $_POST['race'],
            $_POST['age'],
            $_POST['class'],
            $_POST['level'],
            $_POST['campaign'],
            $_POST['background'],
        ]);

        $character_id = $pdo->lastInsertId();

        //Adding character's attributes
        $stmt = $pdo->prepare("INSERT INTO attributes (
            character_id,
            strength,
            dexterity,
            constitution,
            intelligence,
            wisdom,
            charisma
        ) VALUES (?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $character_id,
            $_POST['strength'],
            $_POST['dexterity'],
            $_POST['constitution'],
            $_POST['intelligence'],
            $_POST['wisdom'],
            $_POST['charisma'],
        ]);

        $_SESSION['message'] = "Character created successfully!";
        header("Location: /RPG-Character-Management-System/index.php");
        exit();

    } catch (PDOException $e){
        echo "Insertion error; " + $e;
    }

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Character</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css" rel="stylesheet">  
</head>


<?php require_once("../Templates/navbar.php"); ?>

<body data-bs-theme='dark'>

    <div class="container mt-4">
        
        <!-- Form -->
        <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Create New Character</h5>
                    <form method="POST" id="characterForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Character Name</label>
                                    <input type="text" name="name" class="form-control" placeholder=<?php echo $character['name']?> value=<?php echo $character['name']?>>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Race</label>
                                    <input type="text" name="race" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Age</label>
                                    <input type="text" name="age" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Level</label>
                                    <input type="text" name="level" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Class</label>
                                    <select name="class" class="form-control" required>
                                        <option value="Artificer">Artificer</option>
                                        <option value="Barbarian">Barbarian</option>
                                        <option value="Bard">Bard</option>
                                        <option value="Blood Hunter">Blood Hunter</option>
                                        <option value="Cleric">Cleric</option>
                                        <option value="Druid">Druid</option>
                                        <option value="Fighter">Fighter</option>
                                        <option value="Monk">Monk</option>
                                        <option value="Paladin">Paladin</option>
                                        <option value="Ranger">Ranger</option>
                                        <option value="Rogue">Rogue</option>
                                        <option value="Sorcerer">Sorcerer</option>
                                        <option value="Warlock">Warlock</option>
                                        <option value="Wizard">Wizard</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Strength</label>
                                            <input type="number" name="strength" class="form-control" min="1" max="20" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Dexterity</label>
                                            <input type="number" name="dexterity" class="form-control" min="1" max="20" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Constitution</label>
                                            <input type="number" name="constitution" class="form-control" min="1" max="20" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Intelligence</label>
                                            <input type="number" name="intelligence" class="form-control" min="1" max="20" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Wisdom</label>
                                            <input type="number" name="wisdom" class="form-control" min="1" max="20" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Charisma</label>
                                            <input type="number" name="charisma" class="form-control" min="1" max="20" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Campaign</label>
                                        <input type="text" name="campaign" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        

                        <div class="mb-3">
                            <label class="form-label">Background Story</label>
                            <textarea name="background" class="form-control" rows="3"></textarea>
                        </div>


                        <button type="submit" name="add_character" class="btn btn-primary">Create Character</button>

                    </form>
                </div>
            </div>

    </div>

    

</body>
</html>
