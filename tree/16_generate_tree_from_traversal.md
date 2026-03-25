# 🏗️ Generating a Tree from Traversal

Reconstructing a binary tree from its traversal data is a fundamental problem in tree algorithms.

---

## 🌊 1. From Level Order (Iterative)
This is the most intuitive method. We use a **Queue** to track which node's children we are currently creating.

### **The Algorithm (Iterative):**
1.  Create the **Root** node and push it to a Queue.
2.  While the Queue is not empty:
    -   Pop a node `p`.
    -   Ask for left child data. If it's valid (not `-1`), create a new node, link it as `p->lchild`, and push it to the Queue.
    -   Ask for right child data. If it's valid (not `-1`), create a new node, link it as `p->rchild`, and push it to the Queue.

### **C++ Implementation:**
```cpp
Node* CreateFromLevelOrder() {
    std::queue<Node*> q;
    int x;
    std::cout << "Enter root value: ";
    std::cin >> x;
    Node *root = new Node{nullptr, x, nullptr};
    q.push(root);

    while (!q.empty()) {
        Node *p = q.front(); q.pop();

        // Left Child
        std::cout << "Enter left child of " << p->data << " (-1 for NULL): ";
        std::cin >> x;
        if (x != -1) {
            p->lchild = new Node{nullptr, x, nullptr};
            q.push(p->lchild);
        }

        // Right Child
        std::cout << "Enter right child of " << p->data << " (-1 for NULL): ";
        std::cin >> x;
        if (x != -1) {
            p->rchild = new Node{nullptr, x, nullptr};
            q.push(p->rchild);
        }
    }
    return root;
}
```

---

## 🎯 2. From Pre-order + In-order (Recursive)
You cannot reconstruct a unique tree from only one DFS traversal (Pre, In, or Post). You need a pair, and one of them **MUST** be In-order.

### **Why In-order?**
-   **Pre-order** tells us which node is the **Root** (the first element).
-   **In-order** tells us which nodes are on the **Left** and **Right** of that root.

### **Recursive Strategy:**
1.  Pick the next root from **Pre-order**.
2.  Find its index in **In-order**.
3.  All elements to the left of that index in In-order form the **Left Subtree**.
4.  All elements to the right form the **Right Subtree**.
5.  Recurse.

---

## ⚖️ Summary of Uniqueness
-   **Pre + In**: ✅ Unique Tree
-   **Post + In**: ✅ Unique Tree
-   **Pre + Post**: ❌ NOT Unique (multiple trees can have same Pre/Post)
