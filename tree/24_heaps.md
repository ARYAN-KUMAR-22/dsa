# 📚 Heaps - Priority Magic!

## 🧒 Imagine a Ticket Queue (For 5-Year-Olds)

Imagine a **special queue where important people go first**:
- Emergency patient? 🚑 Goes to FRONT!
- Regular patient? Waits in line
- VIP? Also goes to FRONT!
- Anyone can join, but PRIORITY matters!

**Heap** = Clever queue that prioritizes = **Smart line!** ⏭️

---

## What is a Heap?

### Simple Definition

A **Heap** is a **complete binary tree** where:
- Every parent is **smaller** (min-heap) or **larger** (max-heap) than children
- Complete = all levels full except last
- Perfect for efficiently getting min/max
- Used in priority queues, sorting (heapsort), Dijkstra's algorithm

### Types of Heaps

#### Min-Heap: Parent < Children
```
       1 (smallest at top!)
      / \
     3   5
    / \ /
   7  9 8
```
**Root = minimum value**

#### Max-Heap: Parent > Children
```
       9 (largest at top!)
      / \
     7   5
    / \ /
   1  3 4
```
**Root = maximum value**

---

## Why Not Use Sorted Array?

| Operation | Sorted Array | Heap |
|-----------|:---:|:---:|
| **Get min** | O(1) | O(1) |
| **Add element** | O(n) insert + sort | O(log n) |
| **Remove min** | O(n) shift | O(log n) |
| **Heavy insert/remove** | Slow! | Fast! |

**Heap wins for dynamic data!**

---

## Heaps in Array Form

### Why Store in Array?

**Complete tree property** = perfect for arrays!

```
Tree structure:        Array:
       1              [1, 3, 5, 7, 9, 8, ...]
      / \              0  1  2  3 4  5
     3   5
    / \ /
   7  9 8

Index relationships:
- Parent of i: (i-1)/2
- Left child of i: 2*i+1
- Right child of i: 2*i+2
```

### Array to Tree Mapping

```cpp
// For 0-based indexing
int parent(int i) { return (i - 1) / 2; }
int leftChild(int i) { return 2 * i + 1; }
int rightChild(int i) { return 2 * i + 2; }
```

---

## Heap Operations

### Operation 1: Insert (Add Element)

**Strategy**: Add at end, then "bubble up"

```
Insert 2 into min-heap:

Before:
       1
      / \
     3   5
    / \
   7   9

Add 2 at end:
       1
      / \
     3   5
    / \ /
   7   9 2

Bubble up (2 < 3):
       1
      / \
     2   5
    / \ /
   7  9 3

Done! 2 < parent, stop.
```

#### C++ Implementation

```cpp
void insertMinHeap(vector<int>& heap, int value) {
    heap.push_back(value);  // Add at end
    
    int i = heap.size() - 1;
    
    // Bubble up while smaller than parent
    while (i > 0 && heap[i] < heap[(i - 1) / 2]) {
        swap(heap[i], heap[(i - 1) / 2]);
        i = (i - 1) / 2;
    }
}
```

**Time**: O(log n) - at most go up height

---

### Operation 2: Remove Min (Extract Top)

**Strategy**: Remove root, move last to top, "bubble down"

```
Remove min from:
       1
      / \
     3   5
    / \ /
   7  9 2

Move last (2) to top, remove:
       2
      / \
     3   5
    / \
   7   9

Bubble down (2 > 3, compare with smaller child):
       3
      / \
     2   5
    / \
   7   9

Check again (2 < 7,9), done!
```

#### C++ Implementation

```cpp
int removeMinHeap(vector<int>& heap) {
    if (heap.empty()) return -1;
    
    int min_val = heap[0];
    heap[0] = heap.back();
    heap.pop_back();
    
    int i = 0;
    while (true) {
        int smallest = i;
        int left = 2 * i + 1;
        int right = 2 * i + 2;
        
        // Find smallest among parent and children
        if (left < heap.size() && heap[left] < heap[smallest]) {
            smallest = left;
        }
        if (right < heap.size() && heap[right] < heap[smallest]) {
            smallest = right;
        }
        
        // If parent is smallest, stop
        if (smallest == i) break;
        
        swap(heap[i], heap[smallest]);
        i = smallest;
    }
    
    return min_val;
}
```

**Time**: O(log n)

---

### Operation 3: Heapify (Build Heap from Array)

**Naive way**: Insert each element = O(n log n)

**Smart way**: Start from bottom, bubble down = O(n)!

```cpp
void heapify(vector<int>& arr) {
    int n = arr.size();
    
    // Start from last non-leaf node
    for (int i = (n / 2) - 1; i >= 0; i--) {
        bubbleDown(arr, i);
    }
}

void bubbleDown(vector<int>& heap, int i) {
    while (true) {
        int smallest = i;
        int left = 2 * i + 1;
        int right = 2 * i + 2;
        
        if (left < heap.size() && heap[left] < heap[smallest]) {
            smallest = left;
        }
        if (right < heap.size() && heap[right] < heap[smallest]) {
            smallest = right;
        }
        
        if (smallest == i) break;
        
        swap(heap[i], heap[smallest]);
        i = smallest;
    }
}
```

**Time**: O(n) - amazing!

---

## Complete Heap Implementation

```cpp
#include <iostream>
#include <vector>
#include <algorithm>
using namespace std;

class MinHeap {
private:
    vector<int> heap;
    
    int parent(int i) { return (i - 1) / 2; }
    int leftChild(int i) { return 2 * i + 1; }
    int rightChild(int i) { return 2 * i + 2; }
    
    void bubbleUp(int i) {
        while (i > 0 && heap[i] < heap[parent(i)]) {
            swap(heap[i], heap[parent(i)]);
            i = parent(i);
        }
    }
    
    void bubbleDown(int i) {
        while (true) {
            int smallest = i;
            int left = leftChild(i);
            int right = rightChild(i);
            
            if (left < heap.size() && heap[left] < heap[smallest]) {
                smallest = left;
            }
            if (right < heap.size() && heap[right] < heap[smallest]) {
                smallest = right;
            }
            
            if (smallest == i) break;
            
            swap(heap[i], heap[smallest]);
            i = smallest;
        }
    }
    
public:
    void insert(int value) {
        heap.push_back(value);
        bubbleUp(heap.size() - 1);
    }
    
    int extractMin() {
        if (heap.empty()) return -1;
        
        int min_val = heap[0];
        heap[0] = heap.back();
        heap.pop_back();
        
        if (!heap.empty()) {
            bubbleDown(0);
        }
        
        return min_val;
    }
    
    int peekMin() {
        return heap.empty() ? -1 : heap[0];
    }
    
    void heapify(vector<int>& arr) {
        heap = arr;
        for (int i = (heap.size() / 2) - 1; i >= 0; i--) {
            bubbleDown(i);
        }
    }
    
    void print() {
        for (int val : heap) {
            cout << val << " ";
        }
        cout << endl;
    }
    
    int size() { return heap.size(); }
};

class MaxHeap {
private:
    vector<int> heap;
    
    int parent(int i) { return (i - 1) / 2; }
    int leftChild(int i) { return 2 * i + 1; }
    int rightChild(int i) { return 2 * i + 2; }
    
    void bubbleUp(int i) {
        while (i > 0 && heap[i] > heap[parent(i)]) {
            swap(heap[i], heap[parent(i)]);
            i = parent(i);
        }
    }
    
    void bubbleDown(int i) {
        while (true) {
            int largest = i;
            int left = leftChild(i);
            int right = rightChild(i);
            
            if (left < heap.size() && heap[left] > heap[largest]) {
                largest = left;
            }
            if (right < heap.size() && heap[right] > heap[largest]) {
                largest = right;
            }
            
            if (largest == i) break;
            
            swap(heap[i], heap[largest]);
            i = largest;
        }
    }
    
public:
    void insert(int value) {
        heap.push_back(value);
        bubbleUp(heap.size() - 1);
    }
    
    int extractMax() {
        if (heap.empty()) return -1;
        
        int max_val = heap[0];
        heap[0] = heap.back();
        heap.pop_back();
        
        if (!heap.empty()) {
            bubbleDown(0);
        }
        
        return max_val;
    }
    
    int peekMax() {
        return heap.empty() ? -1 : heap[0];
    }
    
    void print() {
        for (int val : heap) {
            cout << val << " ";
        }
        cout << endl;
    }
};

// Main program
int main() {
    MinHeap minHeap;
    
    cout << "=== Min-Heap Operations ===\n" << endl;
    
    vector<int> values = {50, 30, 70, 20, 40, 60, 80};
    cout << "Inserting: ";
    for (int val : values) {
        cout << val << " ";
        minHeap.insert(val);
    }
    
    cout << "\n\nHeap (array form): ";
    minHeap.print();
    
    cout << "Min element: " << minHeap.peekMin() << endl;
    
    cout << "\nExtracting min elements:\n";
    while (minHeap.size() > 0) {
        cout << minHeap.extractMin() << " ";
    }
    cout << "\n(Notice: sorted order!)\n" << endl;
    
    // Max-Heap example
    cout << "\n=== Max-Heap Operations ===\n" << endl;
    
    MaxHeap maxHeap;
    cout << "Inserting: ";
    for (int val : values) {
        cout << val << " ";
        maxHeap.insert(val);
    }
    
    cout << "\n\nHeap (array form): ";
    maxHeap.print();
    
    cout << "Max element: " << maxHeap.peekMax() << endl;
    
    cout << "\nExtracting max elements:\n";
    while (maxHeap.size() > 0) {
        cout << maxHeap.extractMax() << " ";
    }
    cout << "\n(Reverse sorted!)\n" << endl;
    
    // Heapify example
    cout << "\n=== Heapify (Build heap from array) ===\n" << endl;
    
    MinHeap heapifiedHeap;
    vector<int> arr = {64, 34, 25, 12, 22, 11, 90};
    cout << "Original array: ";
    for (int x : arr) cout << x << " ";
    cout << endl;
    
    heapifiedHeap.heapify(arr);
    cout << "Heapified: ";
    heapifiedHeap.print();
    cout << "(Heap property achieved in O(n)!)" << endl;
    
    return 0;
}
```

---

## Priority Queue (Built on Heap!)

### C++ STL Priority Queue

```cpp
#include <queue>
#include <vector>

// Max-Heap by default
priority_queue<int> maxPQ;
maxPQ.push(50);
maxPQ.push(30);
cout << maxPQ.top();  // 50 (max)

// Min-Heap
priority_queue<int, vector<int>, greater<int>> minPQ;
minPQ.push(50);
minPQ.push(30);
cout << minPQ.top();  // 30 (min)
```

---

## Heap Sort

**Use heap to sort!**

```cpp
void heapSort(vector<int>& arr) {
    int n = arr.size();
    
    // Build heap - O(n)
    for (int i = (n / 2) - 1; i >= 0; i--) {
        bubbleDown(arr, i, n);
    }
    
    // Extract one by one - O(n log n)
    for (int i = n - 1; i > 0; i--) {
        swap(arr[0], arr[i]);
        bubbleDown(arr, 0, i);
    }
}
```

**Time**: O(n log n) - same as quicksort/mergesort
**Space**: O(1) - in-place!

---

## Heap Properties & Analysis

| Property | Value |
|----------|-------|
| **Height** | O(log n) |
| **Insert** | O(log n) |
| **Remove** | O(log n) |
| **Peek** | O(1) |
| **Heapify** | O(n) |
| **Sort** | O(n log n) |
| **Space** | O(n) |

---

## Real-World Applications

### 1. **Priority Queues** ⭐⭐⭐
```cpp
// Task scheduling
priority_queue<Task> taskQueue;
taskQueue.push({priority: 10, task: "urgent"});
```

### 2. **Dijkstra's Algorithm**
Efficient shortest path using min-heap

### 3. **Prim's Algorithm**
Minimum spanning tree uses heap

### 4. **Heap Sort**
Efficient O(n log n) sorting

### 5. **Median Finding**
Two heaps (min + max) to track median

### 6. **Top K Elements**
Min-heap of size K to find top K

---

## 🎯 LeetCode Problems

### Problem 1: Kth Largest Element
**Link**: [LeetCode 215 - Kth Largest Element in Array](https://leetcode.com/problems/kth-largest-element-in-an-array/)

**Difficulty**: Medium

**Solution**: Min-heap of size K

```cpp
int findKthLargest(vector<int>& nums, int k) {
    priority_queue<int, vector<int>, greater<int>> minHeap;
    
    for (int num : nums) {
        minHeap.push(num);
        if (minHeap.size() > k) {
            minHeap.pop();
        }
    }
    
    return minHeap.top();
}
```

---

### Problem 2: Merge K Sorted Lists
**Link**: [LeetCode 23 - Merge k Sorted Lists](https://leetcode.com/problems/merge-k-sorted-lists/)

**Difficulty**: Hard

**Use**: Min-heap to always get smallest next node

---

### Problem 3: Top K Frequent Elements
**Link**: [LeetCode 347 - Top K Frequent Elements](https://leetcode.com/problems/top-k-frequent-elements/)

**Difficulty**: Medium

**Solution**: Min-heap + frequency map

```cpp
vector<int> topKFrequent(vector<int>& nums, int k) {
    unordered_map<int, int> freq;
    for (int num : nums) freq[num]++;
    
    priority_queue<pair<int,int>, vector<pair<int,int>>, greater<pair<int,int>>> minHeap;
    
    for (auto& p : freq) {
        minHeap.push({p.second, p.first});
        if (minHeap.size() > k) {
            minHeap.pop();
        }
    }
    
    vector<int> result;
    while (!minHeap.empty()) {
        result.push_back(minHeap.top().second);
        minHeap.pop();
    }
    return result;
}
```

---

### Problem 4: Find Median from Data Stream
**Link**: [LeetCode 295 - Find Median from Data Stream](https://leetcode.com/problems/find-median-from-data-stream/)

**Difficulty**: Hard

**Solution**: Max-heap (left) + Min-heap (right)

---

## Min-Heap vs Max-Heap

| Use Case | Type |
|----------|:---:|
| **Kth largest** | Min-heap |
| **Kth smallest** | Max-heap |
| **Priority queue (high priority first)** | Max-heap |
| **Priority queue (low priority first)** | Min-heap |
| **Sorting ascending** | Min-heap |
| **Sorting descending** | Max-heap |

---

## Complete Heap vs Other Data Structures

| Feature | Array | Linked List | BST | Heap |
|---------|:---:|:---:|:---:|:---:|
| **Get min** | O(n) | O(n) | O(log n) | O(1) |
| **Insert** | O(n) | O(1) | O(log n) | O(log n) |
| **Remove min** | O(n) | O(1) | O(log n) | O(log n) |
| **Space** | O(n) | O(n) | O(n) | O(n) |
| **Ordered** | Can be | No | Yes | Partial |

**Heap is optimal for min/max + insert/remove!**

---

## Key Takeaways

1. **Complete binary tree** = perfect for arrays
2. **Heap property**: parent < children (min) or parent > children (max)
3. **O(log n) insert/remove** - better than sorted array
4. **Array mapping**: parent(i) = (i-1)/2, left(i) = 2i+1
5. **Heapify**: O(n) to build from array
6. **Priority queues** use heaps internally
7. **Heap sort**: O(n log n) in-place

---

## Interview Tips

**Common Questions:**
1. "Implement min-heap"
2. "Find Kth largest/smallest"
3. "Merge K sorted lists"
4. "Top K elements"
5. "Heap sort"

**Key Points to Mention:**
- "I'd use a heap for efficient min/max extraction"
- "O(log n) per operation - better than array"
- "Can build heap in O(n) time"
- "Priority queues are implemented with heaps"

---

## Practice Path

**Level 1**: Understand heap structure & properties
**Level 2**: Implement insert & extract operations
**Level 3**: Build heap from array (heapify)
**Level 4**: Solve LeetCode 215, 347
**Level 5**: LeetCode 23, 295 (complex problems)

Heaps are one of the **most practically useful** data structures in computer science! 🏆