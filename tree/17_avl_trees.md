# 🌳 AVL Trees - Keeping BSTs Balanced

## 🧒 The Problem (Explain to 5-Year-Old)

Remember our "Magic Box" from BST lesson? 

**Problem**: What if you keep adding numbers **only bigger than the last one**?

```
50
  \
   70
     \
      80
        \
        100
```

Now it's not a tree anymore - it's a **line**! 🪜 No more magic speed!

**Solution**: **AVL Trees** automatically **fix themselves** to stay balanced!

---

## What is an AVL Tree?

**AVL = Adelson-Velsky and Landis** (the people who invented it)

### Simple Definition

An **AVL Tree** is a **self-balancing BST** where:
- Every node has a **balance factor**
- Balance factor = Height of left - Height of right
- Balance factor must be **-1, 0, or +1** (never 2 or -2!)
- If it goes outside range → **automatically rotate** to fix!

### Balance Factor Calculation

```
     A
    / \
   B   C
  /
 D

Height of B's subtree (left) = 2
Height of C's subtree (right) = 1
Balance Factor of A = 2 - 1 = +1 ✓ (Valid: between -1 and +1)
```

---

## Understanding Height & Balance

### Visual Example

```mermaid
graph TD
    A((50)) --> B((30))
    A --> C((70))
    B --> D((20))
    B --> E((40))
    C --> F((60))
    C --> G((80))
    
    style A fill:#ff6b6b
    style B fill:#4ecdc4
    style C fill:#4ecdc4
    style D fill:#ffe66d
    style E fill:#ffe66d
    style F fill:#ffe66d
    style G fill:#ffe66d
```

**Balance Factors**:
- Node 20: height = 1, BF = 0 (no children)
- Node 30: height = 2, BF = 0 (balanced)
- Node 50: height = 3, BF = 0 (perfectly balanced!)

---

## The Problem Scenario

When not balanced:

```mermaid
graph TD
    A((50)) --> B((60))
    B --> C((70))
    C --> D((80))
    D --> E((90))
    
    style A fill:#ff6b6b
    style B fill:#ffcccc
    style C fill:#ffcccc
    style D fill:#ffcccc
    style E fill:#ffe66d
```

**Balance Factors** (from bottom to top):
- Node 80: BF = -1 ✓
- Node 70: BF = 0 - 1 = -1 ✓
- Node 60: BF = 0 - 2 = **-2** ❌ (Unbalanced!)
- Node 50: BF = 0 - 3 = **-3** ❌ (Very unbalanced!)

**Speed problem**: Searching becomes O(n) like a line! 😟

---

## Solution: Rotations

When a node becomes unbalanced, we **rotate** the tree to fix it!

### Type 1: Left Rotation (Right-Heavy)

**When to use**: Balance factor = -2 (right side too heavy)

**Before Rotation**:
```
      A                      B
       \                    / \
        B    --------->    A   C
         \
          C
```

**C++ Code**:
```cpp
Node* leftRotate(Node* node) {
    // node is A, node->right is B
    Node* rightChild = node->right;
    
    // Rotate
    node->right = rightChild->left;      // A's right becomes B's left
    rightChild->left = node;              // B's left becomes A
    
    // Update heights
    node->height = max(height(node->left), height(node->right)) + 1;
    rightChild->height = max(height(rightChild->left), height(rightChild->right)) + 1;
    
    return rightChild;  // B is new root
}
```

### Type 2: Right Rotation (Left-Heavy)

**When to use**: Balance factor = +2 (left side too heavy)

**Before Rotation**:
```
        A                      B
       /        --------->    / \
      B                      C   A
     /
    C
```

**C++ Code**:
```cpp
Node* rightRotate(Node* node) {
    // node is A, node->left is B
    Node* leftChild = node->left;
    
    // Rotate
    node->left = leftChild->right;       // A's left becomes B's right
    leftChild->right = node;              // B's right becomes A
    
    // Update heights
    node->height = max(height(node->left), height(node->right)) + 1;
    leftChild->height = max(height(leftChild->left), height(leftChild->right)) + 1;
    
    return leftChild;  // B is new root
}
```

### Type 3: Left-Right Rotation (Mixed)

**When to use**: Left child is heavy, its right child is heavier

```
     A              A              B
    /              /              / \
   B    -left->   C    -right->  C   A
    \            /
     C          B
```

**C++ Code**:
```cpp
// First rotate left child to the left
// Then rotate node to the right
Node* leftRightRotate(Node* node) {
    node->left = leftRotate(node->left);
    return rightRotate(node);
}
```

### Type 4: Right-Left Rotation (Mixed)

**When to use**: Right child is heavy, its left child is heavier

```
   A                 A                 B
    \       -right->  \       -left->  / \
     B                 B              A   C
    /                   \
   C                     C
```

**C++ Code**:
```cpp
Node* rightLeftRotate(Node* node) {
    node->right = rightRotate(node->right);
    return leftRotate(node);
}
```

---

## AVL Insertion with Balancing

### Step-by-Step Example

**Insert 50, 30, 20** (becomes unbalanced):

```
Step 1: Insert 50
    50

Step 2: Insert 30
    50              BF = 0 (balanced)
   /
  30

Step 3: Insert 20
    50              BF = +1... wait!
   /
  30
 /
20
        
        Actually BF = +2 (unbalanced!)
        Need RIGHT rotation!
        
    30              BF = 0 (balanced!)
   /  \
  20   50
```

### Insertion Algorithm

```cpp
Node* insert(Node* node, int value) {
    // Step 1: Normal BST insert
    if (node == NULL) {
        return new Node(value);
    }
    
    if (value < node->data) {
        node->left = insert(node->left, value);
    } 
    else if (value > node->data) {
        node->right = insert(node->right, value);
    } 
    else {
        return node;  // Duplicate
    }
    
    // Step 2: Update height
    node->height = max(height(node->left), height(node->right)) + 1;
    
    // Step 3: Calculate balance factor
    int bf = height(node->left) - height(node->right);
    
    // Step 4: If unbalanced, fix with rotations
    
    // Left-heavy
    if (bf > 1) {
        // Check if child is right-heavy (left-right case)
        if (height(node->left->left) < height(node->left->right)) {
            return leftRightRotate(node);
        }
        return rightRotate(node);
    }
    
    // Right-heavy
    if (bf < -1) {
        // Check if child is left-heavy (right-left case)
        if (height(node->right->right) < height(node->right->left)) {
            return rightLeftRotate(node);
        }
        return leftRotate(node);
    }
    
    return node;  // Still balanced
}
```

---

## Complete AVL Tree Implementation

```cpp
#include <iostream>
#include <algorithm>
#include <queue>
using namespace std;

struct Node {
    int data;
    Node* left;
    Node* right;
    int height;
    
    Node(int val) : data(val), left(NULL), right(NULL), height(1) {}
};

class AVLTree {
private:
    Node* root;
    
    int height(Node* node) {
        return (node == NULL) ? 0 : node->height;
    }
    
    int getBalanceFactor(Node* node) {
        if (node == NULL) return 0;
        return height(node->left) - height(node->right);
    }
    
    Node* leftRotate(Node* node) {
        Node* rightChild = node->right;
        node->right = rightChild->left;
        rightChild->left = node;
        
        node->height = max(height(node->left), height(node->right)) + 1;
        rightChild->height = max(height(rightChild->left), height(rightChild->right)) + 1;
        
        return rightChild;
    }
    
    Node* rightRotate(Node* node) {
        Node* leftChild = node->left;
        node->left = leftChild->right;
        leftChild->right = node;
        
        node->height = max(height(node->left), height(node->right)) + 1;
        leftChild->height = max(height(leftChild->left), height(leftChild->right)) + 1;
        
        return leftChild;
    }
    
    Node* insertHelper(Node* node, int value) {
        // Step 1: Normal BST insert
        if (node == NULL) {
            return new Node(value);
        }
        
        if (value < node->data) {
            node->left = insertHelper(node->left, value);
        } 
        else if (value > node->data) {
            node->right = insertHelper(node->right, value);
        } 
        else {
            return node;
        }
        
        // Step 2: Update height
        node->height = max(height(node->left), height(node->right)) + 1;
        
        // Step 3: Get balance factor
        int bf = getBalanceFactor(node);
        
        // Step 4: Fix if unbalanced
        
        // Left-left case
        if (bf > 1 && getBalanceFactor(node->left) >= 0) {
            return rightRotate(node);
        }
        
        // Left-right case
        if (bf > 1 && getBalanceFactor(node->left) < 0) {
            node->left = leftRotate(node->left);
            return rightRotate(node);
        }
        
        // Right-right case
        if (bf < -1 && getBalanceFactor(node->right) <= 0) {
            return leftRotate(node);
        }
        
        // Right-left case
        if (bf < -1 && getBalanceFactor(node->right) > 0) {
            node->right = rightRotate(node->right);
            return leftRotate(node);
        }
        
        return node;
    }
    
    Node* deleteHelper(Node* node, int value) {
        if (node == NULL) return NULL;
        
        if (value < node->data) {
            node->left = deleteHelper(node->left, value);
        } 
        else if (value > node->data) {
            node->right = deleteHelper(node->right, value);
        } 
        else {
            // Node found
            if (node->left == NULL && node->right == NULL) {
                delete node;
                return NULL;
            }
            
            if (node->left == NULL) {
                Node* temp = node->right;
                delete node;
                return temp;
            }
            
            if (node->right == NULL) {
                Node* temp = node->left;
                delete node;
                return temp;
            }
            
            // Two children
            Node* minNode = node->right;
            while (minNode->left != NULL) {
                minNode = minNode->left;
            }
            
            node->data = minNode->data;
            node->right = deleteHelper(node->right, minNode->data);
        }
        
        // Update height
        node->height = max(height(node->left), height(node->right)) + 1;
        
        // Get balance factor
        int bf = getBalanceFactor(node);
        
        // Fix if unbalanced
        if (bf > 1 && getBalanceFactor(node->left) >= 0) {
            return rightRotate(node);
        }
        
        if (bf > 1 && getBalanceFactor(node->left) < 0) {
            node->left = leftRotate(node->left);
            return rightRotate(node);
        }
        
        if (bf < -1 && getBalanceFactor(node->right) <= 0) {
            return leftRotate(node);
        }
        
        if (bf < -1 && getBalanceFactor(node->right) > 0) {
            node->right = rightRotate(node->right);
            return leftRotate(node);
        }
        
        return node;
    }
    
    bool searchHelper(Node* node, int value) {
        if (node == NULL) return false;
        if (node->data == value) return true;
        
        if (value < node->data) {
            return searchHelper(node->left, value);
        }
        return searchHelper(node->right, value);
    }
    
    void inOrderHelper(Node* node) {
        if (node == NULL) return;
        inOrderHelper(node->left);
        cout << node->data << "(" << getBalanceFactor(node) << ") ";
        inOrderHelper(node->right);
    }
    
    int getHeightHelper(Node* node) {
        return height(node);
    }
    
public:
    AVLTree() : root(NULL) {}
    
    void insert(int value) {
        root = insertHelper(root, value);
    }
    
    void deleteValue(int value) {
        root = deleteHelper(root, value);
    }
    
    bool search(int value) {
        return searchHelper(root, value);
    }
    
    void inOrder() {
        inOrderHelper(root);
        cout << "\n[Note: Numbers in () are balance factors]\n" << endl;
    }
    
    int getHeight() {
        return getHeightHelper(root);
    }
    
    ~AVLTree() {
        deleteAll(root);
    }
    
private:
    void deleteAll(Node* node) {
        if (node == NULL) return;
        deleteAll(node->left);
        deleteAll(node->right);
        delete node;
    }
};

// Main program
int main() {
    AVLTree tree;
    
    cout << "=== AVL Tree Operations ===\n" << endl;
    
    // Insert values
    int values[] = {50, 30, 70, 20, 40, 60, 80, 10};
    cout << "Inserting: ";
    for (int val : values) {
        cout << val << " ";
        tree.insert(val);
    }
    cout << "\n\nIn-order traversal (BF in parentheses):\n";
    tree.inOrder();
    
    cout << "Tree height: " << tree.getHeight() << endl;
    cout << "(Notice: height = log n, stays balanced automatically!)\n" << endl;
    
    // Search
    cout << "Searching for 40: " << (tree.search(40) ? "Found" : "Not found") << endl;
    cout << "Searching for 25: " << (tree.search(25) ? "Found" : "Not found") << endl;
    
    // Delete and observe rebalancing
    cout << "\nDeleting 20...\n";
    tree.deleteValue(20);
    tree.inOrder();
    
    return 0;
}
```

---

## Comparison: BST vs AVL

| Property | BST | AVL |
|:---|:---:|:---:|
| **Search** | O(log n) avg, O(n) worst | O(log n) guaranteed |
| **Insert** | O(log n) avg, O(n) worst | O(log n) guaranteed |
| **Delete** | O(log n) avg, O(n) worst | O(log n) guaranteed |
| **Space** | O(n) | O(n) + height tracking |
| **Rotations** | None | Automatic each unbalanced |
| **Complexity** | Simpler | More complex |
| **Real-world** | Less used | Databases, file systems |

---

## 🎯 LeetCode Problems for AVL Concepts

### Problem 1: Balanced Binary Tree
**Link**: [LeetCode 110 - Balanced Binary Tree](https://leetcode.com/problems/balanced-binary-tree/)

**Principle**: Check if tree is balanced (like AVL!)

```cpp
bool isBalanced(TreeNode* root) {
    return getHeight(root) != -1;
}

int getHeight(TreeNode* root) {
    if (root == NULL) return 0;
    
    int leftHeight = getHeight(root->left);
    int rightHeight = getHeight(root->right);
    
    // Unbalanced
    if (leftHeight == -1 || rightHeight == -1) {
        return -1;
    }
    
    // Check if current node is balanced
    if (abs(leftHeight - rightHeight) > 1) {
        return -1;
    }
    
    return max(leftHeight, rightHeight) + 1;
}
```

---

### Problem 2: Diameter of Binary Tree
**Link**: [LeetCode 543 - Diameter of Binary Tree](https://leetcode.com/problems/diameter-of-binary-tree/)

**Principle**: Find longest path (AVL trees have small diameter!)

```cpp
int diameterOfBinaryTree(TreeNode* root) {
    int diameter = 0;
    getHeight(root, diameter);
    return diameter;
}

int getHeight(TreeNode* root, int& diameter) {
    if (root == NULL) return 0;
    
    int leftHeight = getHeight(root->left, diameter);
    int rightHeight = getHeight(root->right, diameter);
    
    diameter = max(diameter, leftHeight + rightHeight);
    
    return max(leftHeight, rightHeight) + 1;
}
```

---

### Problem 3: Construct Balanced BST from Sorted Array
**Link**: [LeetCode 108 - Convert Sorted Array to Binary Search Tree](https://leetcode.com/problems/convert-sorted-array-to-binary-search-tree/)

**Principle**: Use middle element as root (keeps tree balanced!)

```cpp
TreeNode* sortedArrayToBST(vector<int>& nums) {
    return build(nums, 0, nums.size() - 1);
}

TreeNode* build(vector<int>& nums, int left, int right) {
    if (left > right) return NULL;
    
    int mid = left + (right - left) / 2;
    TreeNode* root = new TreeNode(nums[mid]);
    
    root->left = build(nums, left, mid - 1);
    root->right = build(nums, mid + 1, right);
    
    return root;
}
```

---

## Key Takeaways About AVL Trees

1. **Self-Balancing**: Maintains height ≈ log n automatically
2. **4 Rotation Types**: LL, RR, LR, RL (handle unbalance)
3. **Balance Factor**: left_height - right_height (must be -1, 0, +1)
4. **Every Op is O(log n)**: Guaranteed! (No worst case O(n))
5. **More Complex**: Than BST but guarantees performance
6. **Real World**: Databases, file systems use AVL concepts
7. **Trade-off**: Extra space for height tracking, rotation overhead

---

## Practice Path for AVL Trees

**Level 1**: Understand rotations visually
**Level 2**: Implement insertion with rotations
**Level 3**: Implement deletion with rebalancing
**Level 4**: LeetCode problems (110, 543)
**Level 5**: Compare with Red-Black trees

Now create more LeetCode-focused practical problems!