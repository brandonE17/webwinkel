<?php

// laad init bestand
require_once 'includes/init.php';

// Check ingelogd
$logged_in = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'];

// LOGIN VERWERKING
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $login_error = "Ongeldige gebruikersnaam of wachtwoord!";
    }
}

// LOGOUT
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_logged_in']);
    header("Location: admin.php");
    exit;
}

// PRODUCT TOEVOEGEN
if ($logged_in && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $naam = $_POST['naam'] ?? '';
    $prijs = $_POST['prijs'] ?? 0;
    $categorie = $_POST['categorie'] ?? '';
    $type = $_POST['type'] ?? 'physical';
    $beschrijving = $_POST['beschrijving'] ?? '';
    $gewicht = $_POST['gewicht'] ?? null;
    $bestandsgrootte = $_POST['bestandsgrootte'] ?? null;
    $korting_procent = $_POST['korting_procent'] ?? null;
    
    if ($naam && $prijs && $categorie) {
        try {
            $stmt = $db->prepare("INSERT INTO producten (naam, prijs, categorie, type, gewicht, bestandsgrootte, korting_procent, beschrijving) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$naam, $prijs, $categorie, $type, $gewicht, $bestandsgrootte, $korting_procent, $beschrijving]);
            $success_msg = "Product succesvol toegevoegd!";
        } catch (Exception $e) {
            $error_msg = "Fout bij toevoegen product: " . $e->getMessage();
        }
    } else {
        $error_msg = "Vul alle verplichte velden in!";
    }
}

// PRODUCT VERWIJDEREN
if ($logged_in && isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'] ?? 0;
    try {
        $stmt = $db->prepare("DELETE FROM producten WHERE id = ?");
        $stmt->execute([$product_id]);
        $success_msg = "Product succesvol verwijderd!";
    } catch (Exception $e) {
        $error_msg = "Fout bij verwijderen product!";
    }
}

// PRODUCT BEWERKEN
if ($logged_in && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_product'])) {
    $product_id = $_POST['product_id'] ?? 0;
    $naam = $_POST['naam'] ?? '';
    $prijs = $_POST['prijs'] ?? 0;
    $categorie = $_POST['categorie'] ?? '';
    $type = $_POST['type'] ?? 'physical';
    $beschrijving = $_POST['beschrijving'] ?? '';
    $gewicht = $_POST['gewicht'] ?? null;
    $bestandsgrootte = $_POST['bestandsgrootte'] ?? null;
    $korting_procent = $_POST['korting_procent'] ?? null;
    
    if ($naam && $prijs && $categorie) {
        try {
            $stmt = $db->prepare("UPDATE producten SET naam=?, prijs=?, categorie=?, type=?, gewicht=?, bestandsgrootte=?, korting_procent=?, beschrijving=? WHERE id=?");
            $stmt->execute([$naam, $prijs, $categorie, $type, $gewicht, $bestandsgrootte, $korting_procent, $beschrijving, $product_id]);
            $success_msg = "Product succesvol bijgewerkt!";
        } catch (Exception $e) {
            $error_msg = "Fout bij bijwerken product!";
        }
    } else {
        $error_msg = "Vul alle verplichte velden in!";
    }
}

// Laad alle producten
$producten = [];
try {
    $stmt = $db->query("SELECT * FROM producten ORDER BY naam");
    $producten = $stmt->fetchAll();
} catch (Exception $e) {
    $error_msg = "Fout bij laden producten!";
}

// Product bewerk gegevens (als edit mode)
$edit_product = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    foreach ($producten as $p) {
        if ($p['id'] == $edit_id) {
            $edit_product = $p;
            break;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneel - Webwinkel</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🔧 Admin Paneel</h1>
            <div class="admin-header-links">
                <a href="index.php" class="nav-link">🏠 Terug naar winkel</a>
                <?php if ($logged_in): ?>
                    <a href="admin.php?logout=1" class="nav-link logout-btn">Logout</a>
                <?php endif; ?>
            </div>
        </header>
        
        <main>
            <?php if (!$logged_in): ?>
                <!-- LOGIN FORMULIER -->
                <div class="login-container">
                    <div class="login-box">
                        <h2>Admin Inloggen</h2>
                        
                        <?php if (isset($login_error)): ?>
                            <div class="error-container">
                                <p><?php echo $login_error; ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="form-group">
                                <label for="username">Gebruikersnaam:</label>
                                <input type="text" id="username" name="username" required class="form-input">
                                <small>Demo: admin</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="password">Wachtwoord:</label>
                                <input type="password" id="password" name="password" required class="form-input">
                                <small>Demo: admin123</small>
                            </div>
                            
                            <button type="submit" name="login" value="1" class="btn btn-primary">Inloggen</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <!-- ADMIN DASHBOARD -->
                <div class="admin-dashboard">
                    <h2>Producten Beheer</h2>
                    
                    <?php if (isset($success_msg)): ?>
                        <div class="success-container">
                            <p>✅ <?php echo $success_msg; ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($error_msg)): ?>
                        <div class="error-container">
                            <p>❌ <?php echo $error_msg; ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <!-- VOEG PRODUCT TOE / BEWERK PRODUCT -->
                    <div class="product-form-container">
                        <h3><?php echo $edit_product ? 'Product Bewerken' : 'Nieuw Product Toevoegen'; ?></h3>
                        
                        <form method="POST" class="product-form">
                            <input type="hidden" name="product_id" value="<?php echo $edit_product['id'] ?? ''; ?>">
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="naam">Productnaam *</label>
                                    <input type="text" id="naam" name="naam" required class="form-input" value="<?php echo $edit_product['naam'] ?? ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="prijs">Prijs (€) *</label>
                                    <input type="number" id="prijs" name="prijs" required step="0.01" class="form-input" value="<?php echo $edit_product['prijs'] ?? ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="categorie">Categorie *</label>
                                    <input type="text" id="categorie" name="categorie" required class="form-input" value="<?php echo $edit_product['categorie'] ?? ''; ?>" placeholder="bijv. Boeken, Hardware">
                                </div>
                                <div class="form-group">
                                    <label for="type">Producttype *</label>
                                    <select id="type" name="type" required class="form-input">
                                        <option value="physical" <?php echo ($edit_product['type'] ?? 'physical') === 'physical' ? 'selected' : ''; ?>>Fysiek product</option>
                                        <option value="digital" <?php echo ($edit_product['type'] ?? '') === 'digital' ? 'selected' : ''; ?>>Digitaal product</option>
                                        <option value="discount" <?php echo ($edit_product['type'] ?? '') === 'discount' ? 'selected' : ''; ?>>Korting product</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="beschrijving">Beschrijving</label>
                                <textarea id="beschrijving" name="beschrijving" class="form-input form-textarea"><?php echo $edit_product['beschrijving'] ?? ''; ?></textarea>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="gewicht">Gewicht (kg) - alleen voor fysieke producten</label>
                                    <input type="number" id="gewicht" name="gewicht" step="0.01" class="form-input" value="<?php echo $edit_product['gewicht'] ?? ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="bestandsgrootte">Bestandsgrootte (MB) - alleen voor digitale producten</label>
                                    <input type="number" id="bestandsgrootte" name="bestandsgrootte" class="form-input" value="<?php echo $edit_product['bestandsgrootte'] ?? ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="korting_procent">Korting (%) - alleen voor korting producten</label>
                                <input type="number" id="korting_procent" name="korting_procent" min="0" max="100" class="form-input" value="<?php echo $edit_product['korting_procent'] ?? ''; ?>">
                            </div>
                            
                            <button type="submit" name="<?php echo $edit_product ? 'edit_product' : 'add_product'; ?>" value="1" class="btn btn-success">
                                <?php echo $edit_product ? '💾 Product bijwerken' : '➕ Product toevoegen'; ?>
                            </button>
                            
                            <?php if ($edit_product): ?>
                                <a href="admin.php" class="btn">❌ Annuleren</a>
                            <?php endif; ?>
                        </form>
                    </div>
                    
                    <!-- PRODUCTEN TABEL -->
                    <div class="products-table-container">
                        <h3>Alle Producten (<?php echo count($producten); ?>)</h3>
                        
                        <?php if (empty($producten)): ?>
                            <p>Geen producten gevonden.</p>
                        <?php else: ?>
                            <table class="products-table">
                                <tr>
                                    <th>ID</th>
                                    <th>Naam</th>
                                    <th>Prijs</th>
                                    <th>Categorie</th>
                                    <th>Type</th>
                                    <th>Acties</th>
                                </tr>
                                <?php foreach ($producten as $product): ?>
                                    <tr>
                                        <td>#<?php echo $product['id']; ?></td>
                                        <td><?php echo htmlspecialchars($product['naam']); ?></td>
                                        <td>€<?php echo number_format($product['prijs'], 2, ',', '.'); ?></td>
                                        <td><span class="category-badge"><?php echo htmlspecialchars($product['categorie']); ?></span></td>
                                        <td><?php echo ucfirst($product['type']); ?></td>
                                        <td class="action-buttons">
                                            <a href="admin.php?edit=<?php echo $product['id']; ?>" class="btn-small btn-edit">✏️ Bewerk</a>
                                            <form method="POST" style="display:inline;" onsubmit="return confirm('Zeker weten?');">
                                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                                <button type="submit" name="delete_product" value="1" class="btn-small btn-delete">🗑️ Verwijder</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
