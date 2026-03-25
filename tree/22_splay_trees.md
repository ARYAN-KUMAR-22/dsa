# 🌳 Splay Trees - Self-Adjusting Magic

## 🧒 The Smart Memory Game (For 5-Year-Olds)

Imagine a toy **tree that remembers what you use most**:
- Access a toy? 🎁 It moves to the top!
- Next time you want it? ⚡ It's RIGHT there!
- Not used toys? Goes to the bottom.

**Splay Tree** = BST that moves accessed nodes to top = **smart memory!** 📍

---

## What is a Splay Tree?

### Simple Definition

A **Splay Tree** is a **self-adjusting BST** where:
- Every access (search, insert, delete) moves node to **root**
- Uses rotations to bring node up (splaying operation)
- Amortized O(log n) for operations
- Optimized for **accessing same nodes repeatedly**

### Key Difference from AVL & Red-Black

- **AVL & Red-Black**: Always keep tree balanced globally
- **Splay**: Brings frequently accessed nodes closer (locally optimized)

---

## The Splay Operation

### What is "Splaying"?

**Splay**: Use rotations to move a node from deep in tree to **ROOT**

```
Before Splay:
       1
        \
         5
          \
           10 (splay this)

After Splay:
       10
      /  \
     5    1
     
Now 10 is ROOT!
```

### Splaying Patterns: 3 Rotations

#### Pattern 1: ZIG (Direct Child of Root)

```
If node is right child of root:
    50               10
   /  \             /  \
 20   10    --->   50  30
      / \         /
     30  40      20
```

**Action**: Single right rotation

#### Pattern 2: ZIG-ZIG (Same Side, Zig-Zig)

```
Node is right child of right child:
    50              20
   /  \            /  \
 20    70   --->  50   10
 / \             /
10  30          30
```

**Action**: Rotate grandparent, then parent

#### Pattern 3: ZIG-ZAG (Alternating Sides)

```
Node is right child of left child:
    50              30
   /  \            /  \
 20    70   --->  20   50
   \   
    30
```

**Action**: Like AVL, different zigzag pattern

---

## Splay Tree Operations

### Operation 1: Splay (Bring Node to Root)

```cpp
Node* splay(Node* node, int value) {
    // Find and bring to root
    return splaying(node, value);
}

Node* splaying(Node* node, int value) {
    if (node == NULL) return NULL;
    
    if (value < node->data) {
        if (node->left == NULL) {
            return node;  // Already splay-able
        }
        
        if (value < node->left->data) {
            // ZIG-ZIG (left-left)
            node->left->left = splaying(node->left->left, value);
            node = rightRotate(node);
        } else if (value > node->left->data) {
            // ZIG-ZAG (left-right)
            node->left->right = splaying(node->left->right, value);
            if (node->left->right != NULL) {
                node->left = leftRotate(node->left);
            }
        }
        
        return rightRotate(node);
    } 
    else if (value > node->data) {
        if (node->right == NULL) {
            return node;
        }
        
        if (value > node->right->data) {
            // ZIG-ZIG (right-right)
            node->right->right = splaying(node->right->right, value);
            node = leftRotate(node);
        } else if (value < node->right->data) {
            // ZIG-ZAG (right-left)
            node->right->left = splaying(node->right->left, value);
            if (node->right->left != NULL) {
                node->right = rightRotate(node->right);
            }
        }
        
        return leftRotate(node);
    }
    
    return node;  // Found!
}
```

### Operation 2: Search (Splay After Finding)

```cpp
bool search(int value) {
    root = splay(root, value);
    return root != NULL && root->data == value;
}
```

**Key**: After search, frequently accessed node is at **ROOT**!

### Operation 3: Insert with Splaying

```cpp
Node* insert(int value) {
    if (root == NULL) {
        return root = new Node(value);
    }
    
    root = splay(root, value);
    
    if (root->data == value) {
        return root;  // Already exists
    }
    
    Node* newNode = new Node(value);
    
    if (value < root->data) {
        newNode->right = root;
        newNode->left = root->left;
        root->left = NULL;
    } else {
        newNode->left = root;
        newNode->right = root->right;
        root->right = NULL;
    }
    
    root = newNode;
    return root;
}
```

### Operation 4: Delete with Splaying

```cpp
Node* deleteValue(int value) {
    if (root == NULL) return NULL;
    
    root = splay(root, value);
    
    if (root->data != value) {
        return root;  // Not found
    }
    
    if (root->left == NULL) {
        root = root->right;
    } else {
        Node* temp = root->right;
        root = root->left;
        root = splay(root, value);  // Find max in left
        root->right = temp;
    }
    
    return root;
}
```

---

## Complete Splay Tree Implementation

```cpp
#include <iostream>
#include <algorithm>
using namespace std;

struct Node {
    int data;
    Node* left;
    Node* right;
    
    Node(int val) : data(val), left(NULL), right(NULL) {}
};

class SplayTree {
private:
    Node* root;
    
    Node* rightRotate(Node* node) {
        if (node->left == NULL) return node;
        
        Node* temp = node->left;
        node->left = temp->right;
        temp->right = node;
        
        return temp;
    }
    
    Node* leftRotate(Node* node) {
        if (node->right == NULL) return node;
        
        Node* temp = node->right;
        node->right = temp->left;
        temp->left = node;
        
        return temp;
    }
    
    Node* splaying(Node* node, int value) {
        if (node == NULL) return NULL;
        
        if (value < node->data) {
            if (node->left == NULL) {
                return node;
            }
            
            if (value < node->left->data) {
                node->left->left = splaying(node->left->left, value);
                node = rightRotate(node);
            } else if (value > node->left->data) {
                node->left->right = splaying(node->left->right, value);
                if (node->left->right != NULL) {
                    node->left = leftRotate(node->left);
                }
            }
            
            return (node->left == NULL) ? node : rightRotate(node);
        } 
        else if (value > node->data) {
            if (node->right == NULL) {
                return node;
            }
            
            if (value > node->right->data) {
                node->right->right = splaying(node->right->right, value);
                node = leftRotate(node);
            } else if (value < node->right->data) {
                node->right->left = splaying(node->right->left, value);
                if (node->right->left != NULL) {
                    node->right = rightRotate(node->right);
                }
            }
            
            return (node->right == NULL) ? node : leftRotate(node);
        }
        
        return node;
    }
    
    void inOrderHelper(Node* node) {
        if (node == NULL) return;
        inOrderHelper(node->left);
        cout << node->data << " ";
        inOrderHelper(node->right);
    }
    
    int getHeightHelper(Node* node) {
        if (node == NULL) return 0;
        return 1 + max(getHeightHelper(node->left), 
                       getHeightHelper(node->right));
    }
    
public:
    SplayTree() : root(NULL) {}
    
    void insert(int value) {
        if (root == NULL) {
            root = new Node(value);
            return;
        }
        
        root = splaying(root, value);
        
        if (root->data == value) {
            return;  // Already exists
        }
        
        Node* newNode = new Node(value);
        
        if (value < root->data) {
            newNode->right = root;
            newNode->left = root->left;
            root->left = NULL;
        } else {
            newNode->left = root;
            newNode->right = root->right;
            root->right = NULL;
        }
        
        root = newNode;
    }
    
    bool search(int value) {
        if (root == NULL) return false;
        root = splaying(root, value);
        return root->data == value;
    }
    
    void deleteValue(int value) {
        if (root == NULL) return;
        
        root = splaying(root, value);
        
        if (root->data != value) return;
        
        if (root->left == NULL) {
            root = root->right;
        } else {
            Node* temp = root;
            root = root->left;
            root = splaying(root, value);
            root->right = temp->right;
        }
    }
    
    void inOrder() {
        inOrderHelper(root);
        cout << "\n" << endl;
    }
    
    int getHeight() {
        return getHeightHelper(root);
    }
    
    void printRoot() {
        if (root) {
            cout << "Root is now: " << root->data << endl;
        }
    }
    
    ~SplayTree() {
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
    SplayTree tree;
    
    cout << "=== Splay Tree Operations ===\n" << endl;
    
    int values[] = {50, 30, 70, 20, 40, 60, 80};
    cout << "Inserting: ";
    for (int val : values) {
        cout << val << " ";
        tree.insert(val);
    }
    
    cout << "\n\nInitial in-order:\n";
    tree.inOrder();
    
    cout << "Searching for 20...\n";
    tree.search(20);
    tree.printRoot();  // Should be 20!
    tree.inOrder();
    
    cout << "Height after splay: " << tree.getHeight() << endl;
    cout << "(Notice: 20 moved to root!)\n" << endl;
    
    cout << "Searching for 80...\n";
    tree.search(80);
    tree.printRoot();  // Should be 80!
    tree.inOrder();
    
    cout << "Accessing small values repeatedly optimizes future accesses!" << endl;
    
    return 0;
}
```

---

## Why Splay Trees Are Special

### The "Amortized O(log n)" Concept

**Amortized** = average over many operations

```
Sequential accesses to same element:
1st access: O(n) worst case
2nd access: O(1) best case!
3rd+ access: O(1) each

Compare to AVL/Red-Black:
Every access: O(log n) always

Splay wins when accessing same nodes repeatedly!
```

---

## When to Use Splay Trees

### ✅ Perfect For:
1. **Temporal locality** (access same nodes repeatedly)
2. **Caching patterns** (hot data accessed frequently)
3. **Self-adjusting behavior** (adapts to access patterns)
4. **Competitive analysis** (provably optimal for many sequences)

### ❌ Not Good For:
1. **Uniform random access** (no pattern, splay doesn't help)
2. **No repeated accesses** (each node splayed once)
3. **Worst-case guarantees needed** (amortized, not worst-case)

---

## Splay vs AVL vs Red-Black

| Factor | AVL | Red-Black | Splay |
|--------|:---:|:---:|:---:|
| **Worst case** | O(log n) | O(log n) | O(n)* |
| **Amortized** | O(log n) | O(log n) | O(log n) |
| **Temporal locality** | No | No | YES! |
| **Rotations/access** | High | Low | Medium varies |
| **Space usage** | Normal | Color info | Minimal** |
| **Implementation** | Medium | Complex | Medium |

*Worst-case single operation only  
**Amortized spreading queries

---

## Real-World Applications

### 1. **Compiler Implementation**

When implementing symbol tables:
- Variables accessed in loops repeatedly
- Splay keeps them at top!

### 2. **Cache Implementation**

```cpp
// Recently accessed cache entries splayed to top
SplayTree cache;
cache.search(key);  // Brings to root if accessed again soon
```

### 3. **Network Protocols**

TCP retransmission buffers often resplay recently sent packets

### 4. **Adaptive Data Structures**

Amazon, Google use similar concepts for:
- Recommending frequently viewed products
- Recent search histories

---

## Interesting Property: Competitive Analysis

### Static Optimal BST

Splay trees achieve near-optimal performance compared to:
- **Best possible static BST** (knowing all accesses beforehand)
- Only O(log n) worse than optimal!

---

## Practice Examples

### Example 1: repeated Access Pattern

```cpp
SplayTree tree;
tree.insert(10); tree.insert(20); tree.insert(30);

tree.search(20);  // O(n) worst - might be deep
tree.search(20);  // O(1) - now at root!
tree.search(20);  // O(1) - still at root!

// Every access to 20 is now O(1)!
```

### Example 2: Working Set Effect

```cpp
// Access 5 nodes repeatedly from larger tree
for (int i = 0; i < 1000; i++) {
    tree.search(3);   // Soon at root
    tree.search(7);   // Soon at root
    tree.search(15);  // Soon at root
    tree.search(42);  // Soon at root
    tree.search(99);  // Soon at root
}
// After first pass, all 5 nodes near top
// Remaining 995 accesses: O(1) each!
```

---

## Clever Interview Insights

**"Why would you use Splay Trees?"**
- "When you have **working set locality** - same data accessed repeatedly"
- "Adapts to access patterns automatically"
- "No need to pre-rebalance like AVL"

**"What's the worst case?"**
- "Single access can be O(n)"
- "But amortized over many operations is O(log n)"
- "Like quicksort - good average, bad worst case"

**"How different from AVL?"**
- "AVL always keeps balanced globally"
- "Splay optimizes for current access pattern"
- "Different philosophy: global vs local"

---

## Key Takeaways

1. **Splaying** = moving accessed node to root
2. **3 Patterns**: Zig, Zig-Zig, Zig-Zag
3. **Amortized O(log n)** = average, not worst case
4. **Adapts to patterns** = frequently accessed near top
5. **Different philosophy** from AVL/Red-Black
6. **Competitive** = nearly optimal vs best static tree
7. **Real-world** = caching, symbol tables, protocols

---

## Learning Path

**Level 1**: Understand splaying concept
**Level 2**: Trace zig, zig-zig, zig-zag patterns
**Level 3**: Implement search with splaying
**Level 4**: Implement insert/delete
**Level 5**: Analyze amortized complexity (advanced math!)

Splay trees are **elegant and powerful** - a true gem of data structures!

---

## Fun Fact

> **"Splay trees are like a smart friend who remembers what you use most and puts it in your pocket!"** 🎁

The self-adjusting property makes them magical for real workloads! 📍✨