# 🚀 Tree Interview Questions & Advanced Tricks

## 🧒 Why Company Ask Tree Questions?

**They want to test**:
1. Can you think recursively? (hardest skill!)
2. Do you understand data structures?
3. Can you optimize solutions?
4. Do you handle edge cases?

---

## Interview Question 1: Serialize & Deserialize Tree

**Company**: Amazon, Facebook, Google

**Principle**: Convert tree to string, then back to tree

### Why Companies Ask
- Tests serialization understanding
- Real-world use case (database storage)
- Requires both traversal + reconstruction

### Solution with Pre-Order

```cpp
class Codec {
public:
    // Encodes tree to single string
    string serialize(TreeNode* root) {
        string result;
        serializeHelper(root, result);
        return result;
    }
    
    // Decodes string back to tree
    TreeNode* deserialize(string data) {
        vector<string> nodes;
        stringstream ss(data);
        string node;
        
        while (getline(ss, node, ',')) {
            nodes.push_back(node);
        }
        
        int idx = 0;
        return deserializeHelper(nodes, idx);
    }

private:
    void serializeHelper(TreeNode* node, string& result) {
        if (node == NULL) {
            result += "null,";
            return;
        }
        
        result += to_string(node->val) + ",";
        serializeHelper(node->left, result);
        serializeHelper(node->right, result);
    }
    
    TreeNode* deserializeHelper(vector<string>& nodes, int& idx) {
        if (idx >= nodes.size() || nodes[idx] == "null") {
            idx++;
            return NULL;
        }
        
        TreeNode* node = new TreeNode(stoi(nodes[idx]));
        idx++;
        
        node->left = deserializeHelper(nodes, idx);
        node->right = deserializeHelper(nodes, idx);
        
        return node;
    }
};

// LeetCode: 297 - Serialize and Deserialize Binary Tree
```

### Interview Follow-up Questions
1. "Can you optimize space?" → Use BFS instead (less stack depth)
2. "What if tree is very large?" → Stream-based processing
3. "How would you serialize to JSON?" → Add type information

---

## Interview Question 2: Binary Tree to Linked List

**Company**: Microsoft, Apple

**Principle**: Flatten tree into linked list using right pointers

### Approach 1: Preorder + Reconstruction
```cpp
vector<TreeNode*> nodes;

void preorder(TreeNode* root) {
    if (root == NULL) return;
    nodes.push_back(root);
    preorder(root->left);
    preorder(root->right);
}

void flatten(TreeNode* root) {
    preorder(root);
    
    for (int i = 0; i < nodes.size() - 1; i++) {
        nodes[i]->left = NULL;
        nodes[i]->right = nodes[i + 1];
    }
    
    if (!nodes.empty()) {
        nodes.back()->left = NULL;
        nodes.back()->right = NULL;
    }
}

// LeetCode: 114 - Flatten Binary Tree to Linked List
```

### Approach 2: Recursive (Space Optimal!)
```cpp
TreeNode* rightMost(TreeNode* node) {
    if (node == NULL) return NULL;
    while (node->right != NULL) {
        node = node->right;
    }
    return node;
}

void flatten(TreeNode* root) {
    if (root == NULL || (root->left == NULL && root->right == NULL)) {
        return;
    }
    
    flatten(root->left);
    flatten(root->right);
    
    if (root->left != NULL) {
        TreeNode* leftRightMost = rightMost(root->left);
        leftRightMost->right = root->right;
        root->right = root->left;
        root->left = NULL;
    }
}
```

**Time**: O(n), **Space**: O(1) space (best!)

---

## Interview Question 3: Sum of Left Leaves

**Company**: Adobe, IBM

**Principle**: Only count leaf nodes on the left!

```cpp
int sumOfLeftLeaves(TreeNode* root) {
    if (root == NULL) return 0;
    
    int sum = 0;
    
    // If left child is leaf
    if (root->left != NULL && 
        root->left->left == NULL && 
        root->left->right == NULL) {
        sum += root->left->val;
    }
    
    sum += sumOfLeftLeaves(root->left);
    sum += sumOfLeftLeaves(root->right);
    
    return sum;
}
```

**Key Point**: Distinguish leaf from internal node!

---

## Interview Question 4: Invert/Mirror Tree

**Company**: Google ("This is an easy one" - but important!)

**Principle**: Swap left and right at every node

### Simple Recursive Solution
```cpp
TreeNode* invertTree(TreeNode* root) {
    if (root == NULL) {
        return NULL;
    }
    
    // Swap
    TreeNode* temp = root->left;
    root->left = root->right;
    root->right = temp;
    
    // Invert subtrees
    invertTree(root->left);
    invertTree(root->right);
    
    return root;
}

// LeetCode: 226 - Invert Binary Tree
```

### Iterative with Queue
```cpp
TreeNode* invertTree(TreeNode* root) {
    if (root == NULL) return NULL;
    
    queue<TreeNode*> q;
    q.push(root);
    
    while (!q.empty()) {
        TreeNode* node = q.front();
        q.pop();
        
        swap(node->left, node->right);
        
        if (node->left) q.push(node->left);
        if (node->right) q.push(node->right);
    }
    
    return root;
}
```

---

## Interview Question 5: Binary Tree's Binary Tree

**Company**: Google, Amazon (Hard!)

**Principle**: Check if tree1 is subtree of tree2

```cpp
bool isSubtree(TreeNode* root, TreeNode* subRoot) {
    if (root == NULL) return false;
    if (isSameTree(root, subRoot)) return true;
    
    return isSubtree(root->left, subRoot) || 
           isSubtree(root->right, subRoot);
}

bool isSameTree(TreeNode* p, TreeNode* q) {
    if (p == NULL && q == NULL) return true;
    if (p == NULL || q == NULL) return false;
    
    return (p->val == q->val) && 
           isSameTree(p->left, q->left) && 
           isSameTree(p->right, q->right);
}

// LeetCode: 572 - Subtree of Another Tree
```

**Time**: O(n*m) where n = tree size, m = subtree size
**Optimization**: Merkle hashing reduces to O(n+m)

---

## Interview Question 6: All Paths from Root to Leaf

**Company**: Microsoft, Apple, Oracle

**Principle**: DFS with backtracking

```cpp
vector<string> binaryTreePaths(TreeNode* root) {
    vector<string> result;
    string path = "";
    dfs(root, path, result);
    return result;
}

void dfs(TreeNode* node, string path, vector<string>& result) {
    if (node == NULL) return;
    
    // Add current node
    path += to_string(node->val);
    
    // Leaf node - add to result
    if (node->left == NULL && node->right == NULL) {
        result.push_back(path);
    } else {
        // Non-leaf - add arrow and continue
        dfs(node->left, path + "->", result);
        dfs(node->right, path + "->", result);
    }
}

// LeetCode: 257 - Binary Tree Paths
```

---

## Interview Question 7: Maximum Path Sum

**Company**: Microsoft, Amazon (Hard!)

**Principle**: Track maximum sum including or excluding current node

```cpp
int maxPathSum(TreeNode* root) {
    int maxSum = INT_MIN;
    maxPathHelper(root, maxSum);
    return maxSum;
}

int maxPathHelper(TreeNode* node, int& maxSum) {
    if (node == NULL) return 0;
    
    // Get max from left and right (0 if negative)
    int leftMax = max(0, maxPathHelper(node->left, maxSum));
    int rightMax = max(0, maxPathHelper(node->right, maxSum));
    
    // Maximum path through this node
    int currentMax = node->val + leftMax + rightMax;
    
    // Update global maximum
    maxSum = max(maxSum, currentMax);
    
    // Return maximum path to parent
    return node->val + max(leftMax, rightMax);
}

// LeetCode: 124 - Binary Tree Maximum Path Sum
```

**Tricky Points**:
1. Path must be continuous
2. Can't skip nodes
3. Return value ≠ actual maximum!

---

## Advanced Trick 1: Morris Traversal (O(1) Space!)

**Use Case**: Need to traverse with O(1) space (no recursion stack!)

### In-Order Morris Traversal

```cpp
vector<int> inorderTraversal(TreeNode* root) {
    vector<int> result;
    TreeNode* current = root;
    
    while (current != NULL) {
        if (current->left == NULL) {
            // No left child - process and go right
            result.push_back(current->val);
            current = current->right;
        } else {
            // Find in-order predecessor
            TreeNode* predecessor = current->left;
            while (predecessor->right != NULL && 
                   predecessor->right != current) {
                predecessor = predecessor->right;
            }
            
            if (predecessor->right == NULL) {
                // First time seeing this node
                predecessor->right = current;
                current = current->left;
            } else {
                // Second time - restore tree
                predecessor->right = NULL;
                result.push_back(current->val);
                current = current->right;
            }
        }
    }
    
    return result;
}
```

**Advantages**: O(1) space! (vs O(h) recursive)
**Disadvantages**: Modifies tree temporarily, complex logic

---

## Advanced Trick 2: Segment Trees for Range Queries

**Use Case**: Fast range sum/min/max queries with updates

### Building a Segment Tree

```cpp
class SegmentTree {
private:
    vector<int> tree;
    int n;
    
    void build(vector<int>& arr, int node, int start, int end) {
        if (start == end) {
            tree[node] = arr[start];
        } else {
            int mid = (start + end) / 2;
            build(arr, 2*node, start, mid);
            build(arr, 2*node+1, mid+1, end);
            tree[node] = tree[2*node] + tree[2*node+1];
        }
    }
    
    int query(int node, int start, int end, int l, int r) {
        if (r < start || end < l) {
            return 0;  // Out of range
        }
        
        if (l <= start && end <= r) {
            return tree[node];  // Fully in range
        }
        
        int mid = (start + end) / 2;
        return query(2*node, start, mid, l, r) +
               query(2*node+1, mid+1, end, l, r);
    }
    
    void update(int node, int start, int end, int idx, int val) {
        if (start == end) {
            tree[node] = val;
        } else {
            int mid = (start + end) / 2;
            if (idx <= mid) {
                update(2*node, start, mid, idx, val);
            } else {
                update(2*node+1, mid+1, end, idx, val);
            }
            tree[node] = tree[2*node] + tree[2*node+1];
        }
    }
    
public:
    SegmentTree(vector<int>& arr) {
        n = arr.size();
        tree.resize(4 * n);
        build(arr, 1, 0, n-1);
    }
    
    int query(int l, int r) {
        return query(1, 0, n-1, l, r);
    }
    
    void update(int idx, int val) {
        update(1, 0, n-1, idx, val);
    }
};
```

**Use in Interview**: "I can optimize range queries from O(n) to O(log n)!"

---

## Advanced Trick 3: Fenwick Tree (Binary Indexed Tree)

**Use Case**: Even simpler way to do range sums!

```cpp
class FenwickTree {
private:
    vector<int> tree;
    int n;
    
public:
    FenwickTree(vector<int>& arr) {
        n = arr.size();
        tree.resize(n + 1, 0);
        for (int i = 0; i < n; i++) {
            update(i, arr[i]);
        }
    }
    
    void update(int i, int delta) {
        i++;  // 1-indexed
        while (i <= n) {
            tree[i] += delta;
            i += i & (-i);  // Smart bit trick!
        }
    }
    
    int query(int i) {
        i++;  // 1-indexed
        int sum = 0;
        while (i > 0) {
            sum += tree[i];
            i -= i & (-i);
        }
        return sum;
    }
    
    int rangeQuery(int l, int r) {
        if (l == 0) return query(r);
        return query(r) - query(l - 1);
    }
};
```

**Code**: Way shorter than Segment Tree!
**Performance**: Same O(log n) for both update and query

---

## Tips for Interviewing

### 🎤 When Asked a Tree Question:

1. **Clarify**: "Is it a BST? Can values be negative? Can there be duplicates?"
2. **Example**: "Let me draw a small tree first (5 nodes)"
3. **Approach**: "I'll use DFS/BFS because..."
4. **Implement**: Start simple, then optimize
5. **Test**: "Let me trace through my example"
6. **Optimize**: "Can I do better with space/time?"

### ❌ Common Mistakes:

1. Forgetting NULL checks
2. Mixing up left/right
3. Returning wrong values
4. Not using base cases properly
5. Forgetting to handle edge cases (1 node, empty tree)

### ✅ Interview Wins:

1. "Let me optimize this..." (shows thinking!)
2. Draw diagrams
3. Explain your thinking
4. Test with multiple cases
5. Ask follow-ups

---

## Problem Progression for Interview Prep

### Week 1: Foundations
- 226 (Invert)
- 257 (All Paths)
- 112 (Path Sum)

### Week 2: BST Operations
- 700 (Search BST)
- 701 (Insert BST)
- 450 (Delete BST)

### Week 3: Complex Patterns
- 297 (Serialize)
- 572 (Subtree)
- 124 (Max Path Sum)

### Week 4: Mixed Difficulty
- 114 (Flatten)
- 235 (LCA BST)
- 437 (Path Sum III)

### Week 5: Advanced
- Segment Trees
- Fenwick Trees
- Morris Traversal

---

## Final Interview Tips Table

| Situation | Strategy |
|-----------|----------|
| Tree traversal needed | Ask: DFS or BFS? |
| Space complex | Consider Morris traversal |
| Range queries | Use Segment/Fenwick tree |
| Need recursion | Remember: "What do I return?" |
| Confused | Draw tree with 5 nodes |
| Panic | "Let me write the base case first" |
| Stuck | "Can I use a helper function?" |
| Optimization | "What data structure helps?" |

---

## Master Tree Interview Pattern

```
1. Listen carefully for:
   - Tree type (binary? BST?)
   - Values (integers? negatives?)
   - Repeated values?
   - Modification? (add/delete)
   
2. Clarify with example (5 nodes)

3. Identify pattern:
   - Traversal? → DFS/BFS
   - Path? → DFS + accumulate
   - Comparison? → Recursively check
   - Search? → Use BST property
   - Construction? → Combine methods

4. Write 3 things:
   - Base case (NULL check)
   - Recursive case
   - Combine results

5. Test (your example)

6. Optimize (space/time)

7. Discuss edge cases
```

Master this pattern → Pass tree interviews!

---

## Resources

**LeetCode**: Filter by topic = "Tree"
**YouTube**: "Alvin the Programmer" tree videos
**Books**: "Cracking the Coding Interview" Chapter 4
**Practice**: 20 problems minimum before interview