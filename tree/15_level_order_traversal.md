# 🌊 Level Order Traversal (BFS)

**Level Order Traversal** (or Breadth First Search) visits nodes level-by-level, starting from the root and moving from left to right at each level.

---

## 🛠️ The Queue Algorithm
To visit nodes level-by-level, we use a **Queue** data structure.

### **The Logic:**
1.  **Initialize**: Create an empty Queue and push the Root node into it.
2.  **Loop**: While the Queue is not empty:
    -   **Pop** a node `p` from the Queue.
    -   **Print/Visit** the data in node `p`.
    -   **Push** the left child of `p` (if any) into the Queue.
    -   **Push** the right child of `p` (if any) into the Queue.

---

## 💻 C++ Implementation
This implementation uses a standard `std::queue` for simplicity.

```cpp
#include <iostream>
#include <queue>

struct Node {
    Node *lchild;
    int data;
    Node *rchild;
};

void LevelOrder(Node *root) {
    if (root == nullptr) return;

    std::queue<Node*> q;
    q.push(root);

    while (!q.empty()) {
        Node *p = q.front();
        q.pop();

        std::cout << p->data << " "; // Visit Node

        if (p->lchild) q.push(p->lchild); // Enqueue Left
        if (p->rchild) q.push(p->rchild); // Enqueue Right
    }
}
```

### 📸 Dry Run Example
For a tree: `A -> B, C; B -> D, E`
1.  Queue: `[A]` $\rightarrow$ Pop A, Print **A**. Enqueue B, C.
2.  Queue: `[B, C]` $\rightarrow$ Pop B, Print **B**. Enqueue D, E.
3.  Queue: `[C, D, E]` $\rightarrow$ Pop C, Print **C**.
4.  Queue: `[D, E]` $\rightarrow$ Pop D, Print **D**.
5.  Queue: `[E]` $\rightarrow$ Pop E, Print **E**.
**Result**: `A B C D E` ✅
